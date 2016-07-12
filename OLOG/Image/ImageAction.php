<?php


namespace OLOG\Image;


class ImageAction
{
    static public function getUrl($storage_alias = '([^/]*)', $image_preset_path_in_storage = '(.*)')
    {
        return "/imagemanager/" . $storage_alias . '/' . $image_preset_path_in_storage;
    }


    public function action($storage_alias, $image_preset_path_in_storage)
    {
        $image_manager_config_obj = \OLOG\ImageManager\ImageManagerConfigWrapper::getImageManagerConfigObj();
        
        $storage_aliases_arr = $image_manager_config_obj->getStoragesAliasesArr();
        
        \OLOG\Exits::exit404If(!array_key_exists($storage_alias, $storage_aliases_arr));
        
        $storage_name = $storage_aliases_arr[$storage_alias];
        
        $image_manager_obj = new \OLOG\ImageManager\ImageManager($storage_name);
        
        $image_manager_obj->output($image_preset_path_in_storage);
    }
}