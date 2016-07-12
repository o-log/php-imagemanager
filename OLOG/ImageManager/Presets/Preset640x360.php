<?php


namespace OLOG\ImageManager\Presets;

use OLOG\ImageManager\ImageManagerPresetInterface;

class Preset640x360 implements ImageManagerPresetInterface
{
    public function processImage(\Imagine\Image\ImageInterface $imageObject)
    {
        return $imageObject->thumbnail(new \Imagine\Image\Box(640, 360), \Imagine\Image\ImageInterface::THUMBNAIL_OUTBOUND);
    }

    public function getAlias()
    {
        return '640_360';
    }
}