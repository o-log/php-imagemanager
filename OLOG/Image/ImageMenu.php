<?php


namespace OLOG\Image;

use OLOG\BT\MenuItem;

class ImageMenu implements \OLOG\BT\InterfaceMenu
{
    static public function menuArr()
    {
        return [
            new MenuItem('Images', \OLOG\Image\Pages\Admin\ImageListAction::getUrl())
        ];
    }

}