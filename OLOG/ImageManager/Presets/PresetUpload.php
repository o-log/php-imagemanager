<?php


namespace OLOG\ImageManager\Presets;

use OLOG\ImageManager\ImageManagerPresetInterface;
use OLOG\ImageManager\ImageManagerPresetTrait;

class PresetUpload implements ImageManagerPresetInterface
{
    use ImageManagerPresetTrait;
    
    public function processImage(\Imagine\Image\ImageInterface $imageObject)
    {
        return $imageObject->thumbnail(new \Imagine\Image\Box(2000, 2000));
    }
}