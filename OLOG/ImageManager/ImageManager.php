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

    public function output($image_path_in_storage, $preset_class_name)
    {
        $fullpath = $this->getImagePathInFileSystemByPreset($image_path_in_storage, $preset_class_name);

        if (!file_exists($fullpath)) {
            $image_path_in_file_system = $this->getImagePathInFileSystem($image_path_in_storage);
            \OLOG\Exits::exit404If(!file_exists($image_path_in_file_system));
            $this->moveImageByPreset($image_path_in_storage, $preset_class_name);
        } else {
            $file_create_time = filemtime($fullpath);
            error_log("PHP Fatal error. Request to existing file: " . $fullpath .' (created ta : '.$file_create_time.', request time : '.time().', host: ' .gethostname( ) . ' )'  );
        }
        $ext = pathinfo($fullpath, PATHINFO_EXTENSION);

        $fp = fopen($fullpath, 'rb');
        header("Cache-Control:public");
        header("Cache-Control:max-age=315360000", false);
        header("Content-Type: image/" . $ext);
        header("Content-Length: " . filesize($fullpath));
        fpassthru($fp);
        exit;
    }
    
    public function getImagePathInFileSystemByPreset($image_path_in_storage, $preset_class_name)
    {
        $preset_alias = self::getPresetAliasByClassName($preset_class_name);
        $storage_obj = \OLOG\Storage\StorageFactory::getStorageObjByName($this->getStorageName());
        return $storage_obj->getFullFilePathOrUrlInStorage($preset_alias . DIRECTORY_SEPARATOR . $image_path_in_storage);
    }

    public function getImagePathInFileSystem($image_path_in_storage)
    {
        $storage_obj = \OLOG\Storage\StorageFactory::getStorageObjByName($this->getStorageName());
        return $storage_obj->getFullFilePathOrUrlInStorage($image_path_in_storage);
    }

    public function getImageUrlByPreset($image_path_in_storage, $preset_class_name)
    {
        $preset_alias = self::getPresetAliasByClassName($preset_class_name);
        $storage_alias = ImageManagerConfigWrapper::getStorageAliasByStorageName($this->getStorageName());
        return ImageAction::getUrl($storage_alias, $preset_alias, $image_path_in_storage);
    }

    public static function generateNewImageFileNameAndPath($file_ext)
    {
        $md5_filename = md5(uniqid('image_', true));
        $first_folder_name = substr($md5_filename, 0, 2);
        $second_folder_name = substr($md5_filename, 2, 2);
        $full_folders_path = $first_folder_name . DIRECTORY_SEPARATOR . $second_folder_name;
        return $full_folders_path . DIRECTORY_SEPARATOR . $md5_filename . '.' . $file_ext;
    }

    public function moveImageByPreset($image_path_in_storage, $preset_class_name)
    {
        $preset_alias = self::getPresetAliasByClassName($preset_class_name);
        $image_path_in_file_system = $this->getImagePathInFileSystem($image_path_in_storage);
        $image_path_in_storage = $preset_alias . DIRECTORY_SEPARATOR . $image_path_in_storage;

        $save_params_arr = array('quality' => ImageManagerConfig::getQuality(), 'jpeg_quality' => ImageManagerConfig::getQuality());
        $this->saveImageToStorage($image_path_in_file_system, $image_path_in_storage, $preset_class_name, $save_params_arr);
    }

    public function storeUploadedImage($source_image_file_name, $source_image_file_path, $force_jpeg_image_format = true)
    {
        $save_params_arr = array('quality' => 100, 'jpeg_quality' => 100); // это аплоад, здесь качество из конфига не берем, аплоадим с максимальным качеством
        $file_extension = pathinfo($source_image_file_name, PATHINFO_EXTENSION);
        if ($force_jpeg_image_format) {
            $file_extension = "jpg";
            $save_params_arr['format'] = "jpeg";
        }
        $image_path_in_storage = self::generateNewImageFileNameAndPath($file_extension);

        $default_upload_preset_class_name = ImageManagerConfig::getDefaultUploadPresetClassName();

        $this->saveImageToStorage($source_image_file_path, $image_path_in_storage, $default_upload_preset_class_name, $save_params_arr);

        return $image_path_in_storage;
    }

    protected function saveImageToStorage($source_image_path_in_file_system, $destiantion_image_file_path_in_storage, $preset_class_name, $save_params_arr = ['quality' => 100])
    {
        $imagine_obj = new \Imagine\Gd\Imagine();
        $image = $imagine_obj->open($source_image_path_in_file_system);

        /**
         * @var $image_preset_obj ImageManagerPresetInterface
         */
        $image_preset_obj = new $preset_class_name;
        \OLOG\Assert::assert($image_preset_obj instanceof ImageManagerPresetInterface);

        $image = $image_preset_obj->processImage($image);

        $tmp_dir = ImageManagerConfig::getTempDir();
        if (!file_exists($tmp_dir)) {
            if (!mkdir($tmp_dir, 0755, true)) {
                throw new \Exception('unable to create temp dir ' . $tmp_dir);
            }
        }

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
     * @param $preset_alias
     * @return string
     * @throws \Exception
     */
    public static function getPresetClassNameByAlias($preset_alias)
    {
        $image_presets_class_names_arr = ImageManagerConfig::getImagePresetsClassnamesArr();

        foreach ($image_presets_class_names_arr as $image_preset_class_name) {
            \OLOG\CheckClassInterfaces::exceptionIfClassNotImplementsInterface($image_preset_class_name, ImageManagerPresetInterface::class);

            /**
             * @var $image_preset_obj ImageManagerPresetInterface
             */
            $image_preset_obj = new $image_preset_class_name;
            
            if ($preset_alias == $image_preset_obj->getAlias()) {
                return $image_preset_class_name;
            }
        }

        return '';
    }

    /**
     * @param $preset_class_name
     * @return string
     * @throws \Exception
     */
    protected static function getPresetAliasByClassName($preset_class_name)
    {
        /**
         * @var $image_preset_obj ImageManagerPresetInterface
         */
        $image_preset_obj = new $preset_class_name;
        \OLOG\Assert::assert($image_preset_obj instanceof ImageManagerPresetInterface);

        return $image_preset_obj->getAlias();
    }

    public function storeImageAndCreateImageObj($source_image_file_path, $image_title, $preset_class_name, $force_jpeg_image_format = true)
    {
        $save_params_arr = array('quality' => ImageManagerConfig::getQuality(), 'jpeg_quality' => ImageManagerConfig::getQuality());
        $file_extension = pathinfo($source_image_file_path, PATHINFO_EXTENSION);
        if ($force_jpeg_image_format) {
            $file_extension = "jpg";
            $save_params_arr['format'] = "jpeg";
        }
        $image_path_in_storage = self::generateNewImageFileNameAndPath($file_extension);

        $this->saveImageToStorage($source_image_file_path, $image_path_in_storage, $preset_class_name, $save_params_arr);

        $image_obj = new \OLOG\Image\Image();
        $image_obj->setStorageName($this->getStorageName());
        $image_obj->setFilePathInStorage($image_path_in_storage);
        $image_obj->setTitle($image_title);
        $image_obj->save();

        return $image_obj->getId();
    }
}
