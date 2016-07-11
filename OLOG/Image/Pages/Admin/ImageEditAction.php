<?php


namespace OLOG\Image\Pages\Admin;


use OLOG\ImageManager\ImagePresets;

class ImageEditAction implements
    \OLOG\BT\InterfaceBreadcrumbs,
    \OLOG\BT\InterfacePageTitle
{
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
        return array_merge(ImageListAction::breadcrumbsArr(), [\OLOG\BT::a(self::getUrl($image_id), self::pageTitle($image_id))]);
    }

    public function action($image_id)
    {
        $image_obj = \OLOG\Image\Image::factory($image_id, false);
        \OLOG\Exits::exit404If(!$image_obj);

        $this->image_id = $image_id;

        $form_fields_arr = array();

        $form_fields_arr[] = new \OLOG\CRUD\CRUDFormRow(
            'Название',
            new \OLOG\CRUD\CRUDFormWidgetInput('title')
        );

        $form_fields_arr[] = new \OLOG\CRUD\CRUDFormRow(
            'Изображение',
            new \OLOG\ImageManager\CRUDFormWidgetImageUploader(
                'file_path_in_storage',
                'storage_name',
                \OLOG\ImageManager\ImageManagerConfigWrapper::getAvailableStorageNamesArr(),
                \OLOG\ImageManager\ImageUploadAction::getUrl()
            )
        );

        $storage_name = $image_obj->getStorageName();
        $image_path_in_images = $image_obj->getFilePathInStorage();
        if($storage_name && $image_path_in_images) {
            $image_manager_obj = new \OLOG\ImageManager\ImageManager($image_obj->getStorageName());

            $form_fields_arr[] = new \OLOG\CRUD\CRUDFormRow(
                '',
                new \OLOG\CRUD\CRUDFormWidgetHtml('<img src="' . \OLOG\Sanitize::sanitizeUrl($image_manager_obj->getImageUrlByPreset($image_obj->getFilePathInStorage(), ImagePresets::IMAGE_PRESET_UPLOAD)) . '" width="100%">')
            );
        }
        
        $html = \OLOG\CRUD\CRUDForm::html($image_obj, $form_fields_arr);

        \OLOG\BT\Layout::render($html, $this);
    }
}