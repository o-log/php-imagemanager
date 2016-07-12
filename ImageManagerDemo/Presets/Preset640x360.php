<?php


namespace ImageManagerDemo\Presets;

use OLOG\ImageManager\ImageManagerPresetInterface;

class Preset640x360 implements ImageManagerPresetInterface
{
    public static function processImageByPreset(\Imagine\Image\ImageInterface $imageObject)
    {
        return $imageObject->thumbnail(new \Imagine\Image\Box(640, 360), \Imagine\Image\ImageInterface::THUMBNAIL_OUTBOUND);
    }
}