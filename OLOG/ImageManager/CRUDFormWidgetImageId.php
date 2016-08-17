<?php

namespace OLOG\CRUD;

use OLOG\ImageManager\Presets\Preset320x240;
use OLOG\Sanitize;

class CRUDFormWidgetImageId implements InterfaceCRUDFormWidget
{
    protected $field_name;

    public function __construct($field_name)
    {
        $this->setFieldName($field_name);
    }

    public function html($obj)
    {
        $field_name = $this->getFieldName();
        $field_value = CRUDFieldsAccess::getObjectFieldValue($obj, $field_name);

        $html = '';
        $html .= '<div class="row">';
        $html .= '<div class="col-sm-12">';
        $html .= '<input name="' . Sanitize::sanitizeAttrValue($field_name) . '" class="form-control" value="' . Sanitize::sanitizeAttrValue($field_value) . '"/>';

        if ($field_value) {
            $image_obj = \OLOG\Image\Image::factory($field_value, false);
            $html = '';
            if ($image_obj) {
                $image_url = $image_obj->getImageUrlByPreset(Preset320x240::class);
                if ($image_url != '') {
                    $html = '<img src=' . \OLOG\Sanitize::sanitizeUrl($image_url) . '>';
                }

            }
        }

        $html .= '</div>';
        $html .= '</div>';

        return $html;
    }

    /**
     * @return mixed
     */
    public function getFieldName()
    {
        return $this->field_name;
    }

    /**
     * @param mixed $field_name
     */
    public function setFieldName($field_name)
    {
        $this->field_name = $field_name;
    }

}