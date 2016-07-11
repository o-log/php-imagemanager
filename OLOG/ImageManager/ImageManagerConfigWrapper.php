<?php


namespace OLOG\ImageManager;


class ImageManagerConfigWrapper
{
    /**
     * @return ImageManagerConfig
     * @throws \Exception
     */
    public static function getImageManagerConfigObj()
    {
        $image_manager_config_obj = \OLOG\ConfWrapper::getRequiredValue(ImageManagerConstants::MODULE_NAME);

        \OLOG\Assert::assert($image_manager_config_obj instanceof ImageManagerConfig);

        return $image_manager_config_obj;
    }

    /**
     * @return string
     * @throws \Exception
     */
    public static function getStorageAliasByStorageName($storage_name)
    {
        $image_manager_config_obj = \OLOG\ImageManager\ImageManagerConfigWrapper::getImageManagerConfigObj();
        $storage_aliases_arr = $image_manager_config_obj->getStoragesAliasesArr();

        $storage_alias = array_search($storage_name, $storage_aliases_arr);
        \OLOG\Assert::assert($storage_alias);

        return $storage_alias;
    }

    /**
     * @return array
     */
    public static function getAvailableStorageNamesArr()
    {
        $image_manager_config_obj = \OLOG\ImageManager\ImageManagerConfigWrapper::getImageManagerConfigObj();
        $storage_aliases_arr = $image_manager_config_obj->getStoragesAliasesArr();

        $storage_names_arr = [];
        foreach ($storage_aliases_arr as $storage_alias => $storage_name) {
            $storage_names_arr[$storage_name] = $storage_name;
        }

        return $storage_names_arr;
    }
}