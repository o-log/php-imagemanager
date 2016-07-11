<?php


namespace OLOG\ImageManager;


class ImageAction
{
    static public function getUrl($storage_alias = '([^/]*)', $image_path = '(.*)')
    {
        return "/imagemanager/" . $storage_alias . '/' . $image_path;
    }


    public function action($storage_alias, $image_path)
    {
        $storage_aliases_arr = \OLOG\ConfWrapper::getRequiredValue(ImageManagerConstants::MODULE_NAME . '.' . ImageManagerConfigKeys::STORAGE_ALIASES_ARR);
        
        \OLOG\Exits::exit404If(!array_key_exists($storage_alias, $storage_aliases_arr));
        
        $storage_name = $storage_aliases_arr[$storage_alias];
        
        $image_manager_obj = new \OLOG\ImageManager\ImageManager($storage_name);
        
        $image_manager_obj->output($image_path);
    }
}