<?php


namespace ImageManagerDemo\Presets;

use OLOG\ImageManager\ImageManagerPresetInterface;
use OLOG\ImageManager\ImageManagerPresetTrait;

class Preset300xAuto implements ImageManagerPresetInterface
{
    use ImageManagerPresetTrait;
    
    public function processImage(\Imagine\Image\ImageInterface $imageObject)
    {
        return $imageObject->thumbnail(new \Imagine\Image\Box(300, 2000), \Imagine\Image\ImageInterface::THUMBNAIL_INSET);
    }
}