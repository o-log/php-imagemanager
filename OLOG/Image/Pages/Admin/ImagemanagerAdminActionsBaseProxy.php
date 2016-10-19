<?php

namespace OLOG\Image\Pages\Admin;

use OLOG\CheckClassInterfaces;
use OLOG\ImageManager\ImageManagerConfig;
use OLOG\Layouts\InterfaceMenu;

class ImagemanagerAdminActionsBaseProxy implements InterfaceMenu
{
    static public function menuArr()
    {
        $base_classname = ImageManagerConfig::getAdminActionsBaseClassname();
        if (CheckClassInterfaces::classImplementsInterface($base_classname, InterfaceMenu::class)){
            return $base_classname::menuArr();
        }

        return [];
    }
}