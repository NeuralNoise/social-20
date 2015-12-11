<?php

class urlProcessor
{
    private $urlBits = array();
    private $urlPath;

    public function __construct(Registry $registry)
    {
        $this->registry = $registry;
    }

    //Set URL Path
    public function setURLPath($url)
    {
        $this->urlPath = $url;
    }

    //Get data from current URL
    public function getURLData()
    {
        $urlData = (isset($_GET['page'])) ? $_GET['page'] : '';
        $this->urlPath = $urlData;
        if ($urlData == '') {
            $this->urlBits[] = '';
            $this->urlPath = '';
        } else {
            $urlData = explode('/', $urlData);
            $this->urlBits = $this->array_trim($urlData);
        }
    }

    public function getURLBits()
    {
        return $this->urlBits;
    }

    public function getURLBit($bit)
    {
        return (isset($this->urlBits[$bit])) ? $this->urlBits[$bit] : 0;
    }

    public function getURLPath()
    {
        return $this->urlPath;
    }

    public function array_trim($data)
    {
        while (!empty($data) && strlen(reset($data)) === 0) //Reset returns the value of first array element. Stop if that value isn't zero letters long
        {
            array_shift($data); //Remove the first element of array $data which is probably empty
        }
        while (!empty($data) && strlen(end($data)) === 0) //End returns the value of the last array element. Stop if that value isn't zero letters long
        {
            array_pop($data); //Remove the last element of the array $data which is probably empty
        }
        return $data;
    }

    //Build URL
    public function buildURL($bit, $queryStr, $homePath)
    {
        $homePath = ($homePath = 1) ? $this->registry->getSetting('admin_folder') : '';
        $restPath = '';
        if (is_array($bit)) {
            foreach ($bit as $b) {
                $restPath .= $b . '/';
            }
        } else {
            $restPath = $bit;
        }
        $restPath .= ($queryStr != '') ? '?&' . $queryStr : '';
        return $this->registry->getSetting('siteurl') . $homePath . $restPath;
    }

    public function getFullURL()
    {
        $pageURL = 'http';
        if ($_SERVER["HTTPS"] == "on") {
            $pageURL .= "s";
        }
        $pageURL .= "://";
        if ($_SERVER["SERVER_PORT"] != "80") {
            $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
        } else {
            $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
        }
        return $pageURL;
    }
}

?>