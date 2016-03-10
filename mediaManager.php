<?php
require_once(__DIR__.'/lib/PHPImageWorkshop/ImageWorkshop.php');

class ImageManager
{
    private $type = '';
    private $uploadExtensions = array('png', 'jpg', 'jpeg', 'gif');
    private $uploadTypes = array('image/gif', 'image/jpg', 'image/jpeg', 'image/pjpeg', 'image/png');
    private $image;
    private $name;

    public function __construct()
    {
        //echo 'construct';
    }

    public function loadFromFile($filepath)
    {
        $info = getimagesize($filepath);
        $this->type = $info[2];
        //Find out name;
        $this->image = ImageWorkshop::initFromPath($filepath);
    }

    public function getWidth()
    {
        return $this->image->getWidth();
    }

    public function getHeight()
    {
        return $this->image->getHeight();
    }

    public function resize($x, $y)
    {
        $this->image->resizeInPixel($x, $y, false);
    }

    public function resizeScale($scale)
    {
        //scale = 1 means same size. true for conserving proportions
        $this->resizeByLargestSideInPercent($scale*100, true);
    }

    public function resizeScaleWidth($height)
    {
        $width = $this->getWidth() * ($height / $this->getHeight());
        $this->resize($width, $height);
    }

    public function resizeScaleHeight($width)
    {
        $height = $this->getHeight() * ($width / $this->getHeight());
        $this->resize($width, $height);
    }

    public function scale($scale)
    {
        $height = $this->getHeight() * $scale / 100;
        $width = $this->getWidth() * $scale / 100;
        $this->resize($width, $height);
    }

    //Display the image; called before sending the output to browser
    public function display()
    {
        $type = '';
        switch ($this->type) {
            case IMAGETYPE_JPEG:
                $type = 'image/jpeg';
                break;
            case IMAGETYPE_GIF:
                $type = 'image/gif';
                break;
            case IMAGETYPE_PNG:
                $type = 'image/png';
                break;
        }
        header('Content-type:' . $type);
        $image = $this->image->getResult();
        switch ($this->type) {
            case IMAGETYPE_JPEG:
                imagejpeg($image);
                break;
            case IMAGETYPE_GIF:
                imagegif($image); //$this->gif
                break;
            case IMAGETYPE_PNG:
                imagepng($image); //$this->png
                break;
        }
    }

    //Load image from post data and moves it to another location
    public function loadFromPost($postField, $moveTo, $name_prefix = '')
    {
        if (is_uploaded_file($_FILES[$postField]['tmp_name'])) {
            $i = strrpos($_FILES[$postField]['name'], '.');
            if (!$i) {
                return false; //Since there is no extension showing file type
            } else {
                $l = strlen($_FILES[$postField]['name']) - $i;
                $ext = strtolower(substr($_FILES[$postField]['name'], $i + 1, $l));
                if (in_array($ext, $this->uploadExtensions)) {
                    if (in_array($_FILES[$postField]['type'], $this->uploadTypes)) {
                        $name = str_replace(' ', '', $_FILES[$postField]['name']);
                        $this->name = $name_prefix . '_' . $name;
                        $path = $moveTo . $name_prefix . '_' . $name;
                        move_uploaded_file($_FILES[$postField]['tmp_name'], $path);
                        $this->loadFromFile($path);
                        return true;
                    } else {
                        return false; //Invalid type
                    }
                } else {
                    return false;  //Invalid extension
                }
            }
        } else {
            return false; //File not uploaded
        }
    }

    public function getName()
    {
        return $this->name;
    }

    public function save($location, $type = '', $quality = 100)
    {
        $this->image->save($location, $this->name, true, null, $quality);
    }
}

class GIFManager{
    private $filepath;
    
    public function __construct($filepath, $image){
        $this->filepath = $filepath;
        require_once(__DIR__.'/lib/GifCreator.php');
        require_once(__DIR__.'/lib/GifFrameExtractor.php');
        if(GifFrameExtractor::isAnimatedGif($this->filepath)){
            $gfe = new GifFrameExtractor();
            $frames = $gfe->extract($this->filepath);
            $newFrames = [];
            foreach($frames as $f){
                require_once(__DIR__.'/lib/PHPImageWorkshop/ImageWorkshop.php');
                $layer = ImageWorkshop::initFromResourceVar($f['image']);
                //We use resizeScaleWidth in imagestatus
                //$layer->resizeInPixel($thumbWidth, $thumbHeight, $conserveProportion, $positionX, $positionY, $position);
                //$layer->resizeByNarrowSideInPixel($newNarrowSideWidth, $conserveProportion);
            }
        }
    }
    
    public function resize(){}
}

?>