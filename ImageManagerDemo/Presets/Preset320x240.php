<?php


namespace ImageManagerDemo\Presets;

use OLOG\ImageManager\ImageManagerPresetInterface;

class Preset320x240 implements ImageManagerPresetInterface
{
    public static function processImageByPreset(\Imagine\Image\ImageInterface $imageObject)
    {
        return $imageObject->thumbnail(new \Imagine\Image\Box(320, 240), \Imagine\Image\ImageInterface::THUMBNAIL_OUTBOUND);
    }
}