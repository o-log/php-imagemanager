<?php


namespace ImageManagerDemo\Presets;

use OLOG\ImageManager\ImageManagerPresetInterface;
use OLOG\ImageManager\ImageManagerPresetTrait;

class Preset320x240 implements ImageManagerPresetInterface
{
    use ImageManagerPresetTrait;
    
    public function processImage(\Imagine\Image\ImageInterface $imageObject)
    {
        return $imageObject->thumbnail(new \Imagine\Image\Box(320, 240), \Imagine\Image\ImageInterface::THUMBNAIL_OUTBOUND);
    }
}