<?php

namespace OLOG\ImageManager;


use OLOG\Image\ImageAction;

class ImageManager
{
    protected $storage_name;

    public function __construct($storage_name)
    {
        $storage_obj = \OLOG\Storage\StorageFactory::getStorageObjByName($storage_name);
        \OLOG\Assert::assert($storage_obj instanceof \OLOG\Storage\LocalStorage);

        $this->setStorageName($storage_name);
    }

    /**
     * @return string
     */
    public function getStorageName()
    {
        return $this->storage_name;
    }

    /**
     * @param string $storage_name
     */
    public function setStorageName($storage_name)
    {
        $this->storage_name = $storage_name;
    }

    public function output($image_preset_path_in_storage)
    {
        list($image_path_in_storage, $preset_alias) = $this->acquirePresetNameAndImageNameFromUrl($image_preset_path_in_storage);

        $preset_name = self::getPresetNameByAlias($preset_alias);
        \OLOG\Exits::exit404If(!$preset_name);

        $fullpath = $this->getImagePathInFileSystemByPreset($image_path_in_storage, $preset_name);

        if (!file_exists($fullpath)) {
            $image_path_in_file_system = $this->getImagePathInFileSystem($image_path_in_storage);
            \OLOG\Exits::exit404If(!file_exists($image_path_in_file_system));
            $this->moveImageByPreset($image_path_in_storage, $preset_name);
        }
        $ext = pathinfo($fullpath, PATHINFO_EXTENSION);

        $fp = fopen($fullpath, 'rb');
        header("Content-Type: image/" . $ext);
        header("Content-Length: " . filesize($fullpath));
        fpassthru($fp);
        exit;
    }

    public function acquirePresetNameAndImageNameFromUrl($requested_file_path)
    {
        if (mb_substr($requested_file_path, 0, 1) == '/') {
            $requested_file_path = mb_substr($requested_file_path, 1);
        }

        $file_path_parts_arr = explode('/', $requested_file_path);

        $preset_name = array_shift($file_path_parts_arr);
        $file_path_relative = implode('/', $file_path_parts_arr);

        return array($file_path_relative, $preset_name);
    }

    public function getImagePathInFileSystemByPreset($image_path_in_storage, $preset_name)
    {
        $storage_obj = \OLOG\Storage\StorageFactory::getStorageObjByName($this->getStorageName());
        return $storage_obj->getFullFilePathOrUrlInStorage($preset_name . DIRECTORY_SEPARATOR . $image_path_in_storage);
    }

    public function getImagePathInFileSystem($image_path_in_storage)
    {
        $storage_obj = \OLOG\Storage\StorageFactory::getStorageObjByName($this->getStorageName());
        return $storage_obj->getFullFilePathOrUrlInStorage($image_path_in_storage);
    }

    public function getImageUrlByPreset($image_path_in_storage, $preset_name)
    {
        $preset_alias = \OLOG\ImageManager\ImageManager::getPresetAliasByName($preset_name);
        $storage_alias = \OLOG\ImageManager\ImageManagerConfigWrapper::getStorageAliasByStorageName($this->getStorageName());
        return ImageAction::getUrl($storage_alias, $preset_alias . '/' . $image_path_in_storage);
    }

    public static function generateNewImageFileNameAndPath($file_ext)
    {
        $md5_filename = md5(uniqid('image_', true));
        $first_folder_name = substr($md5_filename, 0, 2);
        $second_folder_name = substr($md5_filename, 2, 2);
        $full_folders_path = $first_folder_name . DIRECTORY_SEPARATOR . $second_folder_name;
        return $full_folders_path . DIRECTORY_SEPARATOR . $md5_filename . '.' . $file_ext;
    }

    public function moveImageByPreset($image_path_in_storage, $preset_name)
    {
        $image_path_in_file_system = $this->getImagePathInFileSystem($image_path_in_storage);
        $image_path_in_storage = $preset_name . DIRECTORY_SEPARATOR . $image_path_in_storage;

        $this->saveImageToStorage($image_path_in_file_system, $image_path_in_storage, $preset_name);
    }

    public function storeUploadedImage($source_image_file_name, $source_image_file_path, $force_jpeg_image_format = true)
    {
        $save_params_arr = array('quality' => 100);
        $file_extension = pathinfo($source_image_file_name, PATHINFO_EXTENSION);
        if ($force_jpeg_image_format) {
            $file_extension = "jpg";
            $save_params_arr['format'] = "jpeg";
        }
        $image_path_in_storage = self::generateNewImageFileNameAndPath($file_extension);

        $image_manager_config_obj = \OLOG\ImageManager\ImageManagerConfigWrapper::getImageManagerConfigObj();
        $default_upload_preset = $image_manager_config_obj->getDefaultUploadPreset();

        $this->saveImageToStorage($source_image_file_path, $image_path_in_storage, $default_upload_preset, $save_params_arr);

        return $image_path_in_storage;
    }

    protected function saveImageToStorage($source_image_path_in_file_system, $destiantion_image_file_path_in_storage, $preset_name, $save_params_arr = ['quality' => 100])
    {
        $image_manager_config_obj = \OLOG\ImageManager\ImageManagerConfigWrapper::getImageManagerConfigObj();
        $tmp_dir = $image_manager_config_obj->getTempDir();

        $imagine_obj = new \Imagine\Gd\Imagine();
        $image = $imagine_obj->open($source_image_path_in_file_system);

        $image_preset_class_name = self::getImagePresetClassNameByPresetName($preset_name);
        $image = $image_preset_class_name::processImageByPreset($image);

        $file_extension = pathinfo($destiantion_image_file_path_in_storage, PATHINFO_EXTENSION);
        // уникальное случайное имя файла
        do {
            $tmp_dest_file = $tmp_dir . 'imagemanager_' . mt_rand(0, 1000000) . '.' . $file_extension;
        } while (file_exists($tmp_dest_file));

        // запись во временный файл, чтобы другой процесс не мог получить доступ к недописанному файлу
        $image->save($tmp_dest_file, $save_params_arr);

        $storage_obj = \OLOG\Storage\StorageFactory::getStorageObjByName($this->getStorageName());
        $storage_obj->copyToStorage($tmp_dest_file, $destiantion_image_file_path_in_storage);

        unlink($tmp_dest_file);
    }

    /**
     * @param $preset_name
     * @return ImageManagerPresetInterface
     * @throws \Exception
     */
    protected static function getImagePresetClassNameByPresetName($preset_name)
    {
        $image_manager_config_obj = \OLOG\ImageManager\ImageManagerConfigWrapper::getImageManagerConfigObj();
        $image_presets_arr = $image_manager_config_obj->getImagePresetsArr();

        \OLOG\Assert::assert(array_key_exists($preset_name, $image_presets_arr));

        $image_preset_class_name = $image_presets_arr[$preset_name];

        \OLOG\CheckClassInterfaces::exceptionIfClassNotImplementsInterface($image_preset_class_name, ImageManagerPresetInterface::class);

        return $image_preset_class_name;
    }

    /**
     * @param $preset_alias
     * @return string
     * @throws \Exception
     */
    protected static function getPresetNameByAlias($preset_alias)
    {
        $image_manager_config_obj = \OLOG\ImageManager\ImageManagerConfigWrapper::getImageManagerConfigObj();
        $image_presets_aliases_arr = $image_manager_config_obj->getImagePresetsAliasesArr();

        if(!array_key_exists($preset_alias, $image_presets_aliases_arr)){
            return '';
        }

        return $image_presets_aliases_arr[$preset_alias];
    }

    /**
     * @param $preset_name
     * @return string
     * @throws \Exception
     */
    protected static function getPresetAliasByName($preset_name)
    {
        $image_manager_config_obj = \OLOG\ImageManager\ImageManagerConfigWrapper::getImageManagerConfigObj();
        $image_presets_aliases_arr = $image_manager_config_obj->getImagePresetsAliasesArr();

        $image_preset_name = array_search($preset_name, $image_presets_aliases_arr);

        \OLOG\Assert::assert($image_preset_name);
        return $image_preset_name;
    }
}
