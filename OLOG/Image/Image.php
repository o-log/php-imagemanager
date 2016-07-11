<?php

namespace OLOG\Image;

use OLOG\ImageManager\ImageManager;

class Image implements
    \OLOG\Model\InterfaceFactory,
    \OLOG\Model\InterfaceLoad,
    \OLOG\Model\InterfaceSave,
    \OLOG\Model\InterfaceDelete
{
    use \OLOG\Model\FactoryTrait;
    use \OLOG\Model\ActiveRecord;
    use \OLOG\Model\ProtectProperties;

    const DB_ID = \OLOG\ImageManager\ImageManagerConstants::DB_NAME_PHPIMAGEMANAGER;
    const DB_TABLE_NAME = 'olog_image_image';

    protected $created_at_ts; // initialized by constructor
    protected $storage_name;
    protected $file_path_in_storage;
    protected $title = "";
    protected $id;

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($value)
    {
        $this->title = $value;
    }

    public function getFilePathInStorage()
    {
        return $this->file_path_in_storage;
    }

    public function setFilePathInStorage($value)
    {
        $this->file_path_in_storage = $value;
    }

    public function getStorageName()
    {
        return $this->storage_name;
    }

    public function setStorageName($value)
    {
        $this->storage_name = $value;
    }

    static public function getAllIdsArrByCreatedAtDesc()
    {
        $ids_arr = \OLOG\DB\DBWrapper::readColumn(
            self::DB_ID,
            'select id from ' . self::DB_TABLE_NAME . ' order by created_at_ts desc'
        );
        return $ids_arr;
    }

    public function __construct()
    {
        $this->created_at_ts = time();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getCreatedAtTs()
    {
        return $this->created_at_ts;
    }

    /**
     * @param string $timestamp
     */
    public function setCreatedAtTs($timestamp)
    {
        $this->created_at_ts = $timestamp;
    }

    public function getImageUrlByPreset($preset_name)
    {
        $file_path_in_storage = $this->getFilePathInStorage();
        //@TODO check or exeption?
        if (!$file_path_in_storage) {
            return '';
        }
        
        $image_manager_obj = new ImageManager($this->getStorageName());
        return $image_manager_obj->getImgUrlByPreset($file_path_in_storage, $preset_name);
    }
}