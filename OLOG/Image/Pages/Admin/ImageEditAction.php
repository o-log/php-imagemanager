<?php

namespace OLOG\Image\Pages\Admin;

use OLOG\InterfaceAction;
use OLOG\Layouts\AdminLayoutSelector;
use OLOG\Layouts\InterfacePageTitle;
use OLOG\Layouts\InterfaceTopActionObj;

class ImageEditAction extends ImagemanagerAdminActionsBaseProxy implements
    InterfaceAction,
    InterfacePageTitle,
    InterfaceTopActionObj
{
    protected $image_id;

    public function topActionObj()
    {
        return new ImageListAction();
    }

    public function __construct($image_id)
    {
        $this->image_id = $image_id;
    }

    public function url()
    {
        return '/admin/image/' . $this->image_id;
    }

    static public function urlMask(){
        return '/admin/image/(\d+)';
    }

    public function pageTitle()
    {
        return 'Image ' . $this->image_id;
    }

    public function action()
    {
        \OLOG\Exits::exit403If(!\OLOG\Auth\Operator::currentOperatorHasAnyOfPermissions([\OLOG\ImageManager\Permissions::PERMISSION_PHPIMAGEMANAGER_MANAGE_IMAGES]));

        $image_id = $this->image_id;

        $image_obj = \OLOG\Image\Image::factory($image_id, false);
        \OLOG\Exits::exit404If(!$image_obj);

        $html = \OLOG\CRUD\CRUDForm::html(
            $image_obj,
            [
                new \OLOG\CRUD\CRUDFormRow(
                    'Название',
                    new \OLOG\CRUD\CRUDFormWidgetInput('title')
                ),
                new \OLOG\CRUD\CRUDFormRow(
                    'Изображение',
                    new \OLOG\ImageManager\CRUDFormWidgetImageUploader(
                        'file_path_in_storage',
                        'storage_name',
                        \OLOG\ImageManager\ImageManagerConfigWrapper::getAvailableStorageNamesArr(),
                        \OLOG\ImageManager\ImageUploadAction::getUrl()
                    )
                ),
                new \OLOG\CRUD\CRUDFormRow(
                    'Строка копирайта',
                    new \OLOG\CRUD\CRUDFormWidgetInput('copyright_text')
                ),
                new \OLOG\CRUD\CRUDFormRow(
                    'Ссылка копирайта',
                    new \OLOG\CRUD\CRUDFormWidgetInput('copyright_url')
                ),
            ]
        );

        AdminLayoutSelector::render($html, $this);
    }
}