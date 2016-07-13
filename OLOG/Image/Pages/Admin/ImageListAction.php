<?php

namespace OLOG\Image\Pages\Admin;

class ImageListAction implements
    \OLOG\BT\InterfaceBreadcrumbs,
    \OLOG\BT\InterfacePageTitle
{
    static public function getUrl()
    {
        return '/admin/images';
    }

    public function currentPageTitle()
    {
        return self::pageTitle();
    }

    public static function pageTitle()
    {
        return 'Images';
    }

    public function currentBreadcrumbsArr()
    {
        return self::breadcrumbsArr();
    }

    public static function breadcrumbsArr()
    {
        return [\OLOG\BT\BT::a('/admin', 'Admin'), \OLOG\BT\BT::a(self::getUrl(), self::pageTitle())];
    }

    public function action()
    {
        \OLOG\Exits::exit403If(!\OLOG\Auth\Operator::currentOperatorHasAnyOfPermissions([\OLOG\ImageManager\Permissions::PERMISSION_PHPIMAGEMANAGER_MANAGE_IMAGES]));
        
        $html = '';
        $html .= \OLOG\CRUD\CRUDTable::html(
            \OLOG\Image\Image::class,
            \OLOG\CRUD\CRUDForm::html(
                new \OLOG\Image\Image,
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
                    )
                ]
            ),
            [
                new \OLOG\CRUD\CRUDTableColumn('id', new \OLOG\CRUD\CRUDTableWidgetText('{this->id}')),
                new \OLOG\CRUD\CRUDTableColumn('Название', new \OLOG\CRUD\CRUDTableWidgetText('{this->title}')),
                new \OLOG\CRUD\CRUDTableColumn('Storage name', new \OLOG\CRUD\CRUDTableWidgetText('{this->storage_name}')),
                new \OLOG\CRUD\CRUDTableColumn('File path in storage', new \OLOG\CRUD\CRUDTableWidgetText('{this->file_path_in_storage}')),
                new \OLOG\CRUD\CRUDTableColumn('Edit', new \OLOG\CRUD\CRUDTableWidgetTextWithLink('Edit', ImageEditAction::getUrl('{this->id}'))),
                new \OLOG\CRUD\CRUDTableColumn('Delete', new \OLOG\CRUD\CRUDTableWidgetDelete()),
            ]
        );

        \OLOG\BT\Layout::render($html, $this);
    }
}