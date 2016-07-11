<?php


namespace ImageManagerDemo;

use OLOG\ImageManager\ImageManagerConfig;
use OLOG\ImageManager\ImageManagerConstants;
use OLOG\ImageManager\ImagePresets;
use OLOG\Storage\LocalStorage;
use OLOG\Storage\StorageConfigKeys;

class ImageManagerDemoCommonConfig
{
    const STORAGE1_NAME = 'STORAGE1_NAME';

    public static function get()
    {
        date_default_timezone_set('Europe/Moscow');

        $conf = [];

        $conf[\OLOG\Model\ModelConstants::MODULE_CONFIG_ROOT_KEY] = array(
            'db' => array(
                \OLOG\ImageManager\ImageManagerConstants::DB_NAME_PHPIMAGEMANAGER => array(
                    'host' => '127.0.0.1',
                    'db_name' => 'db_phpimagemanagerdemo',
                    'user' => 'root',
                    'pass' => '1',
                )
            ),
            'memcache_servers' => array(
                'localhost:11211'
            )
        );

        $conf[StorageConfigKeys::ROOT] = array(
            StorageConfigKeys::STORAGES_ARR => array(
                self::STORAGE1_NAME => new LocalStorage('/mnt/s1/'),
            ),
        );
        
        $conf[ImageManagerConstants::MODULE_NAME] = new ImageManagerConfig(
            [
                's1' => self::STORAGE1_NAME
            ],
            ImagePresets::IMAGE_PRESET_UPLOAD,
            '/tmp/'
        );

        $conf['php-bt'] = [
            'layout_code' => \OLOG\BT\LayoutGentellela::LAYOUT_CODE_GENTELLELA,
            'menu_classes_arr' => [
                \ImageManagerDemo\ImageManagerDemoMenu::class
            ]
        ];
        return $conf;
    }
}