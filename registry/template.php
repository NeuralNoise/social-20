<?php

//require('registry.php');
class Template
{
    private $registry;
    private $page;

    //Include the page class and build the page object to manage the pages
    public function __construct(Registry $registry)
    {
        $this->registry = $registry;
        include('page.php');
        $this->page = new Page($this->registry);
    }

    //Set content based on number of templates and pass template file location
    public function buildFromTemplate()
    {
        $bits = func_get_args();
        $content = '';
        foreach ($bits as $bit) {
            if (strpos($bit, 'views/') === false) {
                $bit = 'templates/' . $bit; //'views/' . $this->registry->getSetting('view') .
            }
            if (file_exists($bit) == true) {
                $content .= file_get_contents($bit);
            }
        }
        $this->page->setContent($content);
    }

    //Add template bit from a view to our page
    public function addTemplateBit($tag, $bit, $data = array())
    {
        if (strpos($bit, 'templates/') === false) {
            $bit = 'templates/' . $bit; // 'views/' . $this->registry->getSetting('view') .
        }
        $this->page->addTemplateBit($tag, $bit, $data);
    }

    //Take template bits from our page and insert them into view. Update content
    public function replaceBits()
    {
        $bits = $this->page->getBits();
        //loop through template bits
        foreach ($bits as $tag => $template) {
            //echo $template['template'] . ' for '. $tag. ' ';
            $templateContent = file_get_contents($template['template']);
            $oldTags = array_keys($template['replacements']);
            $newTags = array();
            //if($template['template'] == 'templates/updates/image.php') {var_dump($templateContent);}
            foreach ($oldTags as $tags) {
                $newTags[] = '{' . $tags . '}';
            }
            $val = array_values($template['replacements']); //var_dump($val);
            $templateContent = str_replace($newTags, $val, $templateContent);
            $newContent = str_replace('{' . $tag . '}', $templateContent, $this->page->getContent());
            $this->page->setContent($newContent);
        }
    }

    //Replace tags in our page with content
    private function replaceTags($pp = false)
    {
        // get the tags in the page
        if ($pp == false) {
            $tags = $this->page->getTags();
        } else {
            $tags = $this->page->getPPTags();
        }
        // go through them all
        if ($tags != NULL || $tags != '') {
            foreach ($tags as $tag => $data) {
                // if the tag is an array, then we need to do more than a simple find and replace!
                if (is_array($data)) {
                    if ($data[0] == 'SQL') {
                        // it is a cached query...replace tags from the database
                        $this->replaceDBTags($tag, $data[1]);
                    } elseif ($data[0] == 'DATA') {
                        // it is some cached data...replace tags from cached data
                        $this->replaceDataTags($tag, $data[1]);
                    }
                } else {
                    // replace the content
                    //if($tag == 'url'){echo $data;} else {echo $tag;}
                    $newContent = str_replace('{' . $tag . '}', $data, $this->page->getContent());
                    // update the pages content
                    $this->page->setContent($newContent);
                }
            }
        }
    }

    /**
     * Replace content on the page with data from the database
     * @param String $tag the tag defining the area of content
     * @param int $cacheId the queries ID in the query cache
     * @return void
     */
    private function replaceDBTags($tag, $cacheId)
    {
        $block = '';
        $blockOld = $this->page->getBlock($tag);
        $apd = $this->page->getAdditionalParsingData();
        $apdkeys = array_keys($apd);
        // foreach record relating to the query...
        while ($tags = $this->registry->getObject('db')->resultsFromCache($cacheId)) {
            $blockNew = $blockOld;
            // Do we have APD tags?
            if (in_array($tag, $apdkeys)) {
                // YES we do!
                foreach ($tags as $ntag => $data) {
                    $blockNew = str_replace("{" . $ntag . "}", $data, $blockNew);
                    // Is this tag the one with extra parsing to be done?
                    if (array_key_exists($ntag, $apd[$tag])) {
                        // YES it is
                        $extra = $apd[$tag][$ntag];
                        // does the tag equal the condition?
                        if ($data == $extra['condition']) {
                            // Yep! Replace the extra tag with the data
                            $blockNew = str_replace("{" . $extra['tag'] . "}",
                                $extra['data'], $blockNew);
                        } else {
                            // remove the extra tag - it aint used!
                            $blockNew = str_replace("{" . $extra['tag'] . "}", '', $blockNew);
                        }
                    }
                }
            } else {
                // create a new block of content with the results replaced into it
                foreach ($tags as $ntag => $data) {
                    $blockNew = str_replace("{" . $ntag . "}", $data, $blockNew);
                }
            }
            $block .= $blockNew;
        }
        $pageContent = $this->page->getContent();
        // remove the seperator in the template, cleaner HTML
        $newContent = str_replace('<!-- START ' . $tag . ' -->' . $blockOld . '<!-- END ' . $tag . ' -->', $block, $pageContent);
        // update the page content
        $this->page->setContent($newContent);
    }

    /**
     * Replace content on the page with data from the cache
     * @param String $tag the tag defining the area of content
     * @param int $cacheId the datas ID in the data cache
     * @return void
     */
    private function replaceDataTags($tag, $cacheId)
    {
        $blockOld = $this->page->getBlock($tag);
        $block = '';
        if ($cacheId >= 0) {
            $tags = $this->registry->getObject('db')->dataFromCache($cacheId);
            if (is_array($tags)) {
                foreach ($tags as $key => $tagsdata) {
                    $blockNew = $blockOld;
                    //echo $key.'=>';
                    //print_r(array_values($tags));
                    foreach ($tagsdata as $field => $data) {
                        $blockNew = str_replace('{' . $field . '}', $data, $blockNew);
                        //echo $field.'=>'.$data.' and '.$blockNew; echo '';
                    }
                    $block .= $blockNew;
                }
            } else {
                $block = '';
            }
        } else {
            $block = '';
        }
        $pageContent = $this->page->getContent();
        $newContent = str_replace('<!-- START ' . $tag . ' -->' . $blockOld . '<!-- END ' . $tag . ' -->', $block, $pageContent);
        $this->page->setContent($newContent);
    }

    public function getPage()
    {
        return $this->page;
    }

    /**
     * Convert an array of data into some tags
     * @param array the data
     * @param string a prefix which is added to field name to create the tag name
     * @return void
     */
    public function dataToTags($data, $prefix)
    {
        foreach ($data as $key => $content) {
            $this->page->addTag($prefix . $key, $content);
        }
    }

    /**
     * Take the title we set in the page object, and insert them into the view
     */
    public function parseTitle()
    {
        $newContent = str_replace('<title>', '<title>' . $this->page->getTitle(), $this->page->getContent());
        $this->page->setContent($newContent);
    }

    /**
     * Parse the page object into some output
     * @return void
     */
    public function parseOutput()
    {
        $this->replaceBits();
        $this->replaceTags(false);
        $this->replaceTags(true);
        $this->replaceBits();
        $this->replaceTags(false);
        $this->replaceTags(true);
        $this->parseTitle();
    }
}

?>