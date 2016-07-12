<?php


namespace OLOG\ImageManager;


class ImageManagerConfig
{
    protected $storages_aliases_arr;
    protected $default_upload_preset_class_name;
    protected $temp_dir;
    protected $image_presets_class_names_arr;

    public function __construct($storages_aliases_arr, $default_upload_preset_class_name, $temp_dir, $image_presets_arr)
    {
        $this->setDefaultUploadPresetClassName($default_upload_preset_class_name);
        $this->setStoragesAliasesArr($storages_aliases_arr);
        $this->setTempDir($temp_dir);
        $this->setImagePresetsClassnamesArr($image_presets_arr);
    }

    /**
     * @return array
     */
    public function getStoragesAliasesArr()
    {
        return $this->storages_aliases_arr;
    }

    /**
     * @param array $storages_aliases_arr
     */
    public function setStoragesAliasesArr($storages_aliases_arr)
    {
        $this->storages_aliases_arr = $storages_aliases_arr;
    }

    /**
     * @return string
     */
    public function getDefaultUploadPresetClassName()
    {
        return $this->default_upload_preset_class_name;
    }

    /**
     * @param string $default_upload_preset_class_name
     */
    public function setDefaultUploadPresetClassName($default_upload_preset_class_name)
    {
        $this->default_upload_preset_class_name = $default_upload_preset_class_name;
    }

    /**
     * @return string
     */
    public function getTempDir()
    {
        return $this->temp_dir;
    }

    /**
     * @param string $temp_dir
     */
    public function setTempDir($temp_dir)
    {
        $this->temp_dir = $temp_dir;
    }

    /**
     * @return ImageManagerPresetInterface[]
     */
    public function getImagePresetsClassnamesArr()
    {
        return $this->image_presets_class_names_arr;
    }

    /**
     * @param ImageManagerPresetInterface[] $image_presets_class_names_arr
     */
    public function setImagePresetsClassnamesArr($image_presets_class_names_arr)
    {
        $parsed_preset_aliases_arr = [];
        foreach ($image_presets_class_names_arr as $image_preset_class_name) {
            \OLOG\CheckClassInterfaces::exceptionIfClassNotImplementsInterface($image_preset_class_name, ImageManagerPresetInterface::class);

            /**
             * @var $image_preset_obj ImageManagerPresetInterface
             */
            $image_preset_obj = new $image_preset_class_name;
            $preset_alias = $image_preset_obj->getAlias();
           
            \OLOG\Assert::assert(
                !in_array($preset_alias, $parsed_preset_aliases_arr),
                'preset with alias ' . $preset_alias . ' alredy exists'
            );

            $parsed_preset_aliases_arr[] = $preset_alias;
        }

        $this->image_presets_class_names_arr = $image_presets_class_names_arr;
    }
}