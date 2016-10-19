<?php

namespace ImageManagerDemo;

use OLOG\Image\ImageMenu;
use OLOG\Layouts\InterfaceMenu;

class ImagemanagerDemoAdminActionsBase implements InterfaceMenu
{
    static public function menuArr()
    {
        return array_merge(ImageMenu::menuArr());
    }
}