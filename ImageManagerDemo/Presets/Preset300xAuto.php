<?php


namespace ImageManagerDemo\Presets;

use OLOG\ImageManager\ImageManagerPresetInterface;

class Preset300xAuto implements ImageManagerPresetInterface
{
    public static function processImageByPreset(\Imagine\Image\ImageInterface $imageObject)
    {
        return $imageObject->thumbnail(new \Imagine\Image\Box(300, 2000), \Imagine\Image\ImageInterface::THUMBNAIL_INSET);
    }
}