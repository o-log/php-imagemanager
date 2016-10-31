<?php


namespace OLOG\ImageManager;


class ImageManagerRouting
{
    static public function register()
    {
        \OLOG\Router::matchAction(ImageAction::class);
        \OLOG\Router::matchAction(ImageUploadAction::class, 0);
        \OLOG\Router::processAction(\OLOG\Image\Pages\Admin\ImageListAction::class, 0);
        \OLOG\Router::processAction(\OLOG\Image\Pages\Admin\ImageEditAction::class, 0);
	    \OLOG\Router::processAction(ImagePathByImageIdAjaxAction::class, 0);
    }
}