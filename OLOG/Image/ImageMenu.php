<?php


namespace OLOG\Image;

use OLOG\Auth\Operator;
use OLOG\Layouts\InterfaceMenu;
use OLOG\Layouts\MenuItem;

class ImageMenu implements InterfaceMenu
{
    static public function menuArr()
    {
        $menu_arr = [];

        if (Operator::currentOperatorHasAnyOfPermissions([\OLOG\ImageManager\Permissions::PERMISSION_PHPIMAGEMANAGER_MANAGE_IMAGES])) {
            $menu_arr[] = new MenuItem('Картинки', (new \OLOG\Image\Pages\Admin\ImageListAction())->url(), [], 'glyphicon glyphicon-picture');
        }

        return $menu_arr;
    }

}