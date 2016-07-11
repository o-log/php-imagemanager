<?php


namespace OLOG\ImageManager;


class ImageUploadAction
{
    static public function getUrl()
    {
        return "/imagemanager/upload";
    }

    public function action()
    {
        \OLOG\Exits::exit403If(!\OLOG\Auth\Operator::currentOperatorHasAnyOfPermissions([\OLOG\ImageManager\Permissions::PERMISSION_PHPIMAGEMANAGER_MANAGE_IMAGES]));
        
        header('Content-Type: application/json; charset=utf-8');
        if (($_SERVER['REQUEST_METHOD'] != 'POST') || empty($_POST)) {
            echo json_encode(array('success' => false, 'error_message' => \OLOG\Sanitize::sanitizeTagContent('Ошибка метода запроса')));
            return;
        }

        if (!array_key_exists('upload_image_file', $_FILES)) {
            echo json_encode(array('success' => false, 'error_message' => 'Файл не выбран'));
            return;
        }
        $upload_image_file_arr = $_FILES["upload_image_file"];

        if (array_key_exists("error", $upload_image_file_arr) && ($upload_image_file_arr["error"] != 0)) {
            echo json_encode(array('success' => false, 'error_message' => 'Upload error: ' . \OLOG\Sanitize::sanitizeAttrValue($upload_image_file_arr["error"])));
            return;
        }

        try {
            \OLOG\POSTAccess::getRequiredPostValue('upload_storage_name');
        } catch (\Exception $e) {
            echo json_encode(array('success' => false, 'error_message' => \OLOG\Sanitize::sanitizeTagContent('Ошибка формы запроса, не указан upload_storage_name')));
            return;
        }

        $upload_storage_name = $_POST['upload_storage_name'];
        try {
            $upload_storage_obj = \OLOG\Storage\StorageFactory::getStorageObjByName($upload_storage_name);
        } catch (\Exception $e) {
            echo json_encode(array('success' => false, 'error_message' => \OLOG\Sanitize::sanitizeTagContent('Storage с именем ' . $upload_storage_name . ' не найден')));
            return;
        }

        $tmp_file_path = $upload_image_file_arr["tmp_name"];
        if (!is_uploaded_file($tmp_file_path)) {
            echo json_encode(array('success' => false, 'error_message' => 'Ошибка is_uploaded_file: ' . \OLOG\Sanitize::sanitizeAttrValue($tmp_file_path)));
            return;
        }
        
        $image_manager_obj = new ImageManager($upload_storage_name);
        $file_path_in_storage = $image_manager_obj->storeUploadedImage($upload_image_file_arr['name'], $tmp_file_path);
        
        $return_arr = array(
            'success' => true,
            'storage_name' => $upload_storage_name,
            'file_path_in_storage' => $file_path_in_storage,
            'image_url' => $image_manager_obj->getImageUrlByPreset($file_path_in_storage, \OLOG\ImageManager\ImagePresets::IMAGE_PRESET_UPLOAD)
        );

        echo json_encode($return_arr);
    }
}