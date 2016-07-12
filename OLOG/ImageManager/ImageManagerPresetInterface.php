<?php


namespace OLOG\ImageManager;


interface ImageManagerPresetInterface
{
    /**
     * @param \Imagine\Image\ImageInterface $imageObject
     * @return \Imagine\Image\ImageInterface
     */
    public static function processImageByPreset(\Imagine\Image\ImageInterface $imageObject);
}