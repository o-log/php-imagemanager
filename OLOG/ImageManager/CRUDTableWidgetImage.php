<?php

namespace OLOG\ImageManager;

use OLOG\CRUD\CRUDCompiler;
use OLOG\CRUD\InterfaceCRUDTableWidget;
use OLOG\ImageManager\Presets\Preset300xAuto;

class CRUDTableWidgetImage implements InterfaceCRUDTableWidget
{
    protected $image_id;
    protected $image_preset_class_name;

    public function html($obj)
    {
        $image_id = CRUDCompiler::compile($this->getImageId(), ['this' => $obj]);

        $image_obj = \OLOG\Image\Image::factory($image_id, false);
        $html = '';
        if ($image_obj && $image_obj->getFilePathInStorage() && $image_obj->getStorageName()) {
            $html = '<img src=' . \OLOG\Sanitize::sanitizeUrl($image_obj->getImageUrlByPreset($this->getImagePresetClassName())) . '>';
        }
        
        return $html;
    }

    public function __construct($image_id, $image_preset_class_name = Preset300xAuto::class)
    {
        $this->setImageId($image_id);
        $this->setImagePresetClassName($image_preset_class_name);
    }

    /**
     * @return int
     */
    public function getImageId()
    {
        return $this->image_id;
    }

    /**
     * @param int $image_id
     */
    public function setImageId($image_id)
    {
        $this->image_id = $image_id;
    }

    /**
     * @return string
     */
    public function getImagePresetClassName()
    {
        return $this->image_preset_class_name;
    }

    /**
     * @param string $image_preset_class_name
     */
    public function setImagePresetClassName($image_preset_class_name)
    {
        $this->image_preset_class_name = $image_preset_class_name;
    }
}