<?php


namespace OLOG\ImageManager;


interface ImageManagerPresetInterface
{
    /**
     * @param \Imagine\Image\ImageInterface $imageObject
     * @return \Imagine\Image\ImageInterface
     */
    public function processImage(\Imagine\Image\ImageInterface $imageObject);
    public function getAlias();
}