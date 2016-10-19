<?php


namespace OLOG\ImageManager;


class ImageManagerConfig
{
    static protected $storages_aliases_arr = [];
    static protected $default_upload_preset_class_name; // TODO: default value
    static protected $temp_dir; // TODO: default value
    static protected $image_presets_class_names_arr = [];
    static protected $quality = 90;
    static protected $admin_actions_base_classname;

    /**
     * @return mixed
     */
    public static function getAdminActionsBaseClassname()
    {
        return self::$admin_actions_base_classname;
    }

    /**
     * @param mixed $admin_actions_base_classname
     */
    public static function setAdminActionsBaseClassname($admin_actions_base_classname)
    {
        self::$admin_actions_base_classname = $admin_actions_base_classname;
    }

    /**
     * @return int
     */
    public static function getQuality()
    {
        return self::$quality;
    }

    /**
     * @param int $quality
     */
    public static function setQuality($quality)
    {
        self::$quality = $quality;
    }

    /**
     * @return mixed
     */
    public static function getStoragesAliasesArr()
    {
        return self::$storages_aliases_arr;
    }

    /**
     * @param mixed $storages_aliases_arr
     */
    public static function setStoragesAliasesArr($storages_aliases_arr)
    {
        self::$storages_aliases_arr = $storages_aliases_arr;
    }

    /**
     * @return mixed
     */
    public static function getDefaultUploadPresetClassName()
    {
        return self::$default_upload_preset_class_name;
    }

    /**
     * @param mixed $default_upload_preset_class_name
     */
    public static function setDefaultUploadPresetClassName($default_upload_preset_class_name)
    {
        self::$default_upload_preset_class_name = $default_upload_preset_class_name;
    }

    /**
     * @return mixed
     */
    public static function getTempDir()
    {
        return self::$temp_dir;
    }

    /**
     * @param mixed $temp_dir
     */
    public static function setTempDir($temp_dir)
    {
        self::$temp_dir = $temp_dir;
    }

    /*
    public function __construct($storages_aliases_arr, $default_upload_preset_class_name, $temp_dir, $image_presets_arr)
    {
        $this->setDefaultUploadPresetClassName($default_upload_preset_class_name);
        $this->setStoragesAliasesArr($storages_aliases_arr);
        $this->setTempDir($temp_dir);
        $this->setImagePresetsClassnamesArr($image_presets_arr);
    }
    */

    /**
     * @return array
     */
    static public function getImagePresetsClassnamesArr()
    {
        return self::$image_presets_class_names_arr;
    }

    /**
     * @param ImageManagerPresetInterface[] $image_presets_class_names_arr
     */
    static public function setImagePresetsClassnamesArr($image_presets_class_names_arr)
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

        self::$image_presets_class_names_arr = $image_presets_class_names_arr;
    }
}