<?php


namespace OLOG\ImageManager\Presets;

use OLOG\ImageManager\ImageManagerPresetInterface;

class PresetUpload implements ImageManagerPresetInterface
{
    public function processImage(\Imagine\Image\ImageInterface $imageObject)
    {
        return $imageObject->thumbnail(new \Imagine\Image\Box(2000, 2000));
    }
    
    public function getAlias()
    {
        return 'upload';
    }
}