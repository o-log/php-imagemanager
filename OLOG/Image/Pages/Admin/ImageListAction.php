<?php

namespace OLOG\Image\Pages\Admin;

use OLOG\InterfaceAction;
use OLOG\Layouts\AdminLayoutSelector;
use OLOG\Layouts\InterfacePageTitle;

class ImageListAction extends ImagemanagerAdminActionsBaseProxy implements
    InterfaceAction,
    InterfacePageTitle
{
    public function url()
    {
        return '/admin/images';
    }

    public function pageTitle()
    {
        return 'Изображения';
    }

    public function action()
    {
        \OLOG\Exits::exit403If(!\OLOG\Auth\Operator::currentOperatorHasAnyOfPermissions([\OLOG\ImageManager\Permissions::PERMISSION_PHPIMAGEMANAGER_MANAGE_IMAGES]));

	    $table_id = 'ImageList_table_3245klb34ojh34y5';
	    $form_id = 'ImageList_form_3245klb34ojh34y5';

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
                            \OLOG\ImageManager\ImageUploadAction::getUrl(),
                            false
                        )
                    )
                ],
	            '',
	            [],
	            $form_id
            ),
            [
                new \OLOG\CRUD\CRUDTableColumn('id', new \OLOG\CRUD\CRUDTableWidgetText('{this->id}')),
                new \OLOG\CRUD\CRUDTableColumn('Название', new \OLOG\CRUD\CRUDTableWidgetHtml('{this->title}<br />{this->copyright_text}<br />{this->copyright_url}')),
                new \OLOG\CRUD\CRUDTableColumn('', new \OLOG\ImageManager\CRUDTableWidgetImage('{this->id}')),
                new \OLOG\CRUD\CRUDTableColumn('Edit', new \OLOG\CRUD\CRUDTableWidgetTextWithLink('Edit', (new ImageEditAction('{this->id}'))->url())),
                new \OLOG\CRUD\CRUDTableColumn('Delete', new \OLOG\CRUD\CRUDTableWidgetDelete()),
            ],
            [],
            'id desc',
	        $table_id
        );

        AdminLayoutSelector::render($html, $this);
    }
}