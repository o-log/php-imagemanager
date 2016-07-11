<?php


namespace ImageManagerDemo;

use OLOG\BT\MenuItem;

class ImageManagerDemoMenu implements \OLOG\BT\InterfaceMenu
{
    static public function menuArr()
    {
        return [
            new MenuItem('Images', \OLOG\Image\Pages\Admin\ImageListAction::getUrl())
        ];
    }

}