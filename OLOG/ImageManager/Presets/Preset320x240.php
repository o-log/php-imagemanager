<?php


namespace OLOG\ImageManager\Presets;

use OLOG\ImageManager\ImageManagerPresetInterface;

class Preset320x240 implements ImageManagerPresetInterface
{
    public function processImage(\Imagine\Image\ImageInterface $imageObject)
    {
        return $imageObject->thumbnail(new \Imagine\Image\Box(320, 240), \Imagine\Image\ImageInterface::THUMBNAIL_OUTBOUND);
    }

    public function getAlias()
    {
        return '320_240';
    }
}