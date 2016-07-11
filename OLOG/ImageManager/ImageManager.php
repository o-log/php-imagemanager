<?php

namespace OLOG\ImageManager;


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

    /**
     * @return string
     * @throws \Exception
     */
    public static function getStorageAliasByStorageName($storage_name)
    {
        $storage_aliases_arr = \OLOG\ConfWrapper::getRequiredValue(ImageManagerConstants::MODULE_NAME . '.' . ImageManagerConfigKeys::STORAGE_ALIASES_ARR);

        $storage_alias = array_search($storage_name, $storage_aliases_arr);
        \OLOG\Assert::assert($storage_alias);

        return $storage_alias;
    }

    public function output($image_preset_path_in_storage)
    {
        list($image_path_in_storage, $preset_name) = $this->acquirePresetNameAndImageNameFromUrl($image_preset_path_in_storage);

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

    public function getImgUrlByPreset($image_path, $preset_name)
    {
        $storage_alias = self::getStorageAliasByStorageName($this->getStorageName());
        return ImageAction::getUrl($storage_alias, $preset_name . '/' . $image_path);
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

        $default_upload_preset = \OLOG\ConfWrapper::getRequiredValue(ImageManagerConstants::MODULE_NAME . '.' . ImageManagerConfigKeys::DEFAULT_UPLOAD_PRESET);
        
        $this->saveImageToStorage($source_image_file_path, $image_path_in_storage, $default_upload_preset, $save_params_arr);

        return $image_path_in_storage;
    }

    protected function saveImageToStorage($source_image_path_in_file_system, $destiantion_image_file_path_in_storage, $preset_name, $save_params_arr = ['quality' => 100])
    {
        $tmp_dir = \OLOG\ConfWrapper::getRequiredValue(ImageManagerConstants::MODULE_NAME . '.' . ImageManagerConfigKeys::TEMP_DIR);

        $imagine_obj = new \Imagine\Gd\Imagine();
        $image = $imagine_obj->open($source_image_path_in_file_system);
        $image = ImagePresets::processImageByPreset($image, $preset_name);

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
}
