<?php


namespace OLOG\Image\Pages\Admin;


class ImageEditAction implements
    \OLOG\BT\InterfaceBreadcrumbs,
    \OLOG\BT\InterfacePageTitle,
    \OLOG\BT\InterfaceUserName
{
    use \OLOG\Auth\Admin\CurrentUserNameTrait;

    protected $image_id;

    static public function getUrl($image_id = '(\d+)')
    {
        return '/admin/image/' . $image_id;
    }

    public function currentPageTitle()
    {
        return self::pageTitle($this->image_id);
    }

    public static function pageTitle($image_id)
    {
        return 'Image ' . $image_id;
    }

    public function currentBreadcrumbsArr()
    {
        return self::breadcrumbsArr($this->image_id);
    }

    public static function breadcrumbsArr($image_id)
    {
        return array_merge(ImageListAction::breadcrumbsArr(), [\OLOG\BT\BT::a(self::getUrl($image_id), self::pageTitle($image_id))]);
    }

    public function action($image_id)
    {
        \OLOG\Exits::exit403If(!\OLOG\Auth\Operator::currentOperatorHasAnyOfPermissions([\OLOG\ImageManager\Permissions::PERMISSION_PHPIMAGEMANAGER_MANAGE_IMAGES]));
        
        $image_obj = \OLOG\Image\Image::factory($image_id, false);
        \OLOG\Exits::exit404If(!$image_obj);

        $this->image_id = $image_id;

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

        \OLOG\BT\Layout::render($html, $this);
    }
}