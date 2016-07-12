<?php


namespace OLOG\ImageManager;


class ImageManagerConfig
{
    protected $storages_aliases_arr;
    protected $default_upload_preset;
    protected $temp_dir;
    protected $image_presets_arr;

    public function __construct($storages_aliases_arr, $default_upload_preset, $temp_dir, $image_presets_arr)
    {
        $this->setDefaultUploadPreset($default_upload_preset);
        $this->setStoragesAliasesArr($storages_aliases_arr);
        $this->setTempDir($temp_dir);
        $this->setImagePresetsArr($image_presets_arr);
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
    public function getDefaultUploadPreset()
    {
        return $this->default_upload_preset;
    }

    /**
     * @param string $default_upload_preset
     */
    public function setDefaultUploadPreset($default_upload_preset)
    {
        $this->default_upload_preset = $default_upload_preset;
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
    public function getImagePresetsArr()
    {
        return $this->image_presets_arr;
    }

    /**
     * @param array $image_presets_arr
     */
    public function setImagePresetsArr($image_presets_arr)
    {
        $this->image_presets_arr = $image_presets_arr;
    }
}