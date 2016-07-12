<?php


namespace OLOG\ImageManager;


class ImageAction
{
    static public function getUrl($storage_alias = '([^/]*)', $preset_alias = '([^/]*)', $image_path_in_storage = '(.*)')
    {
        return "/imagemanager/" . $storage_alias . '/' . $preset_alias . '/' . $image_path_in_storage;
    }

    public function action($storage_alias, $preset_alias, $image_path_in_storage)
    {
        $image_manager_config_obj = ImageManagerConfigWrapper::getImageManagerConfigObj();
        
        $storage_aliases_arr = $image_manager_config_obj->getStoragesAliasesArr();
        
        \OLOG\Exits::exit404If(!array_key_exists($storage_alias, $storage_aliases_arr));
        
        $storage_name = $storage_aliases_arr[$storage_alias];

        $preset_class_name = ImageManager::getPresetClassNameByAlias($preset_alias);
        \OLOG\Exits::exit404If(!$preset_class_name);

        $image_manager_obj = new ImageManager($storage_name);
        
        $image_manager_obj->output($image_path_in_storage, $preset_class_name);
    }
}