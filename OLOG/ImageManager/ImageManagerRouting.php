<?php


namespace OLOG\ImageManager;


class ImageManagerRouting
{
    static public function register()
    {
        \OLOG\Router::matchAction(ImageAction::class, 0);
        \OLOG\Router::matchAction(ImageUploadAction::class, 0);
        \OLOG\Router::matchAction(\OLOG\Image\Pages\Admin\ImageListAction::class, 0);
        \OLOG\Router::matchAction(\OLOG\Image\Pages\Admin\ImageEditAction::class, 0);
    }
}