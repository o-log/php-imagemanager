<?php


namespace OLOG\ImageManager\Presets;

use OLOG\ImageManager\ImageManagerPresetInterface;

class Preset300xAuto implements ImageManagerPresetInterface
{
    public function processImage(\Imagine\Image\ImageInterface $imageObject)
    {
        return $imageObject->thumbnail(new \Imagine\Image\Box(300, 2000), \Imagine\Image\ImageInterface::THUMBNAIL_INSET);
    }

    public function getAlias()
    {
        return '300_auto';
    }
}