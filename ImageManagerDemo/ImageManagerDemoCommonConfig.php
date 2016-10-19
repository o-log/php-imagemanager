<?php


namespace ImageManagerDemo;

use OLOG\BT\LayoutBootstrap;
use OLOG\Cache\CacheConfig;
use OLOG\Cache\MemcacheServerSettings;
use OLOG\DB\DBConfig;
use OLOG\DB\DBSettings;
use OLOG\Image\ImageMenu;
use OLOG\ImageManager\ImageManagerConfig;
use OLOG\ImageManager\ImageManagerConstants;
use OLOG\Layouts\LayoutsConfig;
use OLOG\Storage\LocalStorage;
use OLOG\Storage\StorageConfig;

class ImageManagerDemoCommonConfig
{
    const STORAGE1_NAME = 'STORAGE1_NAME';
    
    public static function init()
    {
        date_default_timezone_set('Europe/Moscow');

        DBConfig::setDBSettingsObj(
            \OLOG\Auth\AuthConstants::DB_NAME_PHPAUTH,
            new DBSettings('localhost', 'db_phpimagemanagerdemo', 'root', '1', 'vendor/o-log/php-auth/db_phpauth.sql')
        );

        DBConfig::setDBSettingsObj(
            ImageManagerConstants::DB_NAME_PHPIMAGEMANAGER,
            new DBSettings('localhost', 'db_phpimagemanagerdemo', 'root', '1')
        );

        /*
        LayoutsConfig::setAdminMenuClassesArr([
            ImageMenu::class
        ]);
        */

        LayoutsConfig::setAdminLayoutClassName(LayoutBootstrap::class);

        ImageManagerConfig::setAdminActionsBaseClassname(ImagemanagerDemoAdminActionsBase::class);

        CacheConfig::addServerSettingsObj(
            new MemcacheServerSettings('localhost', 11211)
        );

        StorageConfig::setStorage(
            self::STORAGE1_NAME,
            new LocalStorage('/mnt/s1/')
        );

        ImageManagerConfig::setStoragesAliasesArr(
            [
                's1' => self::STORAGE1_NAME
            ]
        );

        ImageManagerConfig::setTempDir('/tmp/');

        ImageManagerConfig::setDefaultUploadPresetClassName(\OLOG\ImageManager\Presets\PresetUpload::class);

        ImageManagerConfig::setImagePresetsClassnamesArr(
            [
                \OLOG\ImageManager\Presets\Preset320x240::class,
                \OLOG\ImageManager\Presets\PresetUpload::class,
                \OLOG\ImageManager\Presets\Preset640x360::class,
                \OLOG\ImageManager\Presets\Preset300xAuto::class,
            ]
        );

        /*
        $conf[ImageManagerConstants::MODULE_NAME] = new ImageManagerConfig(
            [
                's1' => self::STORAGE1_NAME
            ],
            \OLOG\ImageManager\Presets\PresetUpload::class,
            '/tmp/',
            [
                \OLOG\ImageManager\Presets\Preset320x240::class,
                \OLOG\ImageManager\Presets\PresetUpload::class,
                \OLOG\ImageManager\Presets\Preset640x360::class,
                \OLOG\ImageManager\Presets\Preset300xAuto::class,

            ]
        );

        $conf[\OLOG\BT\BTConstants::MODULE_NAME] = [
            'layout_class_name' => \OLOG\Gentelella\Layout::class,
            'menu_classes_arr' => [
                \OLOG\Image\ImageMenu::class
            ]
        ];
        */
    }
}