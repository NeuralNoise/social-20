<?php

//Registry class
class Registry
{
    private $objects; //Array of objects
    private $settings; //Array of settings

    /*Create a new object and store in registry. Factory within registry method
    @param String $object in object file prefix
    @param String $key pair for the object
    @return void
    */
    public function setObject($object, $key)
    {
        require_once("registry/" . $object . ".php");
        $this->objects[$key] = new $object($this);
    }

    /*Store the settings
    @param String $setting the setting data
    @param $key the key pair for the setting array
    @return void
    */
    public function setSetting($setting, $key)
    {
        $this->settings[$key] = $setting;
    }

    //Returns the object for the provided key
    public function getObject($key)
    {
        return $this->objects[$key];
    }

    //Returns the settings for the provided key
    public function getSetting($key)
    {
        return $this->settings[$key];
    }

    //Error page
    public function errorPage($heading, $content)
    {
        $this->getObject('template')->buildFromTemplate('header.php', 'message.php', 'footer.php');
        $this->getObject('template')->getPage()->addTag('heading', $heading);
        $this->getObject('template')->getPage()->addTag('content', $content);
    }

    //Redirect User
    public function redirectUser($url, $heading, $message)
    {
        $this->getObject('template')->buildFromTemplate('header.php', 'redirect.php', 'footer.php');
        $this->getObject('template')->getPage()->addTag('heading', $heading);
        $this->getObject('template')->getPage()->addTag('message', $message);
        $this->getObject('template')->getPage()->addTag('redirect', $url);
    }

    //Build URL
    public function buildURL($urlBits, $queryString = array())
    {
        return $this->getObject('url')->buildURL($urlBits, $queryString, false);
    }

    //What the ajax query should expect in return
    public function ajaxReply($tags)
    {
        /*foreach($tags as $field=>$value)
        {
            echo $value;
        }*/
        $this->getObject('template')->buildFromTemplate('ajaxReturn.php');
        foreach ($tags as $field => $value) {
            $this->getObject('template')->getPage()->addTag($field, $value);
        }
        //var_dump($tags);
    }
}

?>
