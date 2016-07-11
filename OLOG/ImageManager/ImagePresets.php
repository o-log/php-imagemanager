<?php

namespace OLOG\ImageManager;

class ImagePresets
{
    const IMAGE_PRESET_604_331 = '604_331';
    const IMAGE_PRESET_219_143 = '219_143';
    const IMAGE_PRESET_131_91 = '131_91';
    const IMAGE_PRESET_260_170 = '260_170';
    const IMAGE_PRESET_305_305 = '305_305';
    const IMAGE_PRESET_510_390 = '510_390';
    const IMAGE_PRESET_800_600 = '800_600';
    const IMAGE_PRESET_622_392 = '622_392';
    const IMAGE_PRESET_160_200 = '160_200';
    const IMAGE_PRESET_160_90 = '160_90';
    const IMAGE_PRESET_128_75 = '128_75';
    const IMAGE_PRESET_280_317 = '280_317';
    const IMAGE_PRESET_38_30 = '38_30';
    const IMAGE_PRESET_120_120_auto = '120_120_auto';
    const IMAGE_PRESET_460_300_auto = '460_300_auto';
    const IMAGE_PRESET_800_800_auto = '800_800_auto';
    const IMAGE_PRESET_FOTOBANK_263_263_auto = 'fotobank263x263Auto';
    const IMAGE_PRESET_50_50 = '50x50';
    const IMAGE_PRESET_212_120 = '212x120';
    const IMAGE_PRESET_109_90 = '109x90';
    const IMAGE_PRESET_500_280 = '500x280';
    const IMAGE_PRESET_500_326 = '500x326';
    const IMAGE_PRESET_970_540 = '970x540';
    const IMAGE_PRESET_auto_129 = 'auto_129';
    const IMAGE_PRESET_690_auto = '690_auto';
    const IMAGE_PRESET_280_175 = '280_175';
    const IMAGE_PRESET_480_270 = '480_270';
    const IMAGE_PRESET_1024_576 = '1024_576';
    const IMAGE_PRESET_1024_512 = '1024_512';

    const IMAGE_PRESET_450_350_auto = "450_350_auto";
    const IMAGE_PRESET_33_24_auto = "33_24_auto";
    const IMAGE_PRESET_100_75_auto = "100_75_auto";
    const IMAGE_PRESET_100_56 = "100_56";

    const IMAGE_PRESET_149_149 = "149_149";
    const IMAGE_PRESET_944_auto = "944_auto";
    const IMAGE_PRESET_960_auto = "960_auto";
    const IMAGE_PRESET_imagecache_960xauto = '960xauto';
    const IMAGE_PRESET_150_150_auto = "150_150_auto";
    const IMAGE_PRESET_144_81 = "144_81";
    const IMAGE_PRESET_imagecache_144x81 = '144x81';
    const IMAGE_PRESET_105_auto = "105_auto";
    const IMAGE_PRESET_96_96_auto = "96_96_auto";
    const IMAGE_PRESET_335_224 = "335_224";
    const IMAGE_PRESET_58_68_auto = "58_68_auto";
    const IMAGE_PRESET_26_26_auto = "26_26_auto";
    const IMAGE_PRESET_115_68 = "115_68";
    const IMAGE_PRESET_140_150_auto = "140_150_auto";
    const IMAGE_PRESET_144_68 = "144_68";
    const IMAGE_PRESET_140_140_auto = "140_140_auto";

    const IMAGE_PRESET_270_152 = "270_152";
    const IMAGE_PRESET_270_152_outbound = "270_152_outbound";
    const IMAGE_PRESET_450_253 = "450_253";
    const IMAGE_PRESET_450x253 = "450x253";
    const IMAGE_PRESET_450_253_auto = "450_253_auto";

    const IMAGE_PRESET_300_160 = "300_160";
    const IMAGE_PRESET_380_180 = "380_180";
    const IMAGE_PRESET_940_430 = "940_430";
    const IMAGE_PRESET_auto_350 = 'auto_350';
    const IMAGE_PRESET_232_129 = '232_129';
    const IMAGE_PRESET_940_600 = '940_600';
    const IMAGE_PRESET_640_360 = '640_360';
    const IMAGE_PRESET_630_420 = '630_420';
    const IMAGE_PRESET_690_388 = '690_388';

    const IMAGE_PRESET_390_260 = "390_260";

    const IMAGE_PRESET_320_240 = '320_240';
    const IMAGE_PRESET_240_135 = '240_135';

    const IMAGE_PRESET_SMARTTV_330_186 = 'smarttv-330x186';
    const IMAGE_PRESET_SMARTTV_330_205 = 'smarttv-330x205';

    const IMAGE_PRESET_120_90 = '120_90';

    const IMAGE_PRESET_190x160 = '190x160';
    const IMAGE_PRESET_90x90 = '90x90';

    const IMAGE_PRESET_550x400 = '550x400';

    const IMAGE_PRESET_550_400_OUT = '550x400_out';

    const IMAGE_PRESET_81_81 = '81_81';

    const IMAGE_PRESET_230_auto = "230_auto";
    const IMAGE_PRESET_190_auto = "190_auto";

    const IMAGE_PRESET_320x180 = "320x180";

    const IMAGE_PRESET_300_auto = "300_auto";
    const IMAGE_PRESET_220_120 = "220_120";
    const IMAGE_PRESET_440_245 = "440_245";
    const IMAGE_PRESET_460_320 = '460_320';
    const IMAGE_PRESET_300_215 = "300_215";
    const IMAGE_PRESET_286_140 = "286_140";
    const IMAGE_PRESET_SOCIALS = "400_400";
    const IMAGE_PRESET_400_400_crop = "400_400_crop";

    const IMAGE_PRESET_317_200 = "317_200";
    const IMAGE_PRESET_auto_150 = 'auto_150';
    const IMAGE_PRESET_317_115 = "317_115";
    const IMAGE_PRESET_150_80 = "150_80";

    const IMAGE_PRESET_444_250 = "444_250";
    const IMAGE_PRESET_auto_53 = "auto_53";
    const IMAGE_PRESET_720_450 = "720_450";
    const IMAGE_PRESET_720_450_crop = "720_450_crop";
    const IMAGE_PRESET_453_255_crop = "453_255_crop";
    const IMAGE_PRESET_372_247_crop = "372_247_crop";
    const IMAGE_PRESET_720_260 = "720_260";

    const IMAGE_PRESET_680_454 = '680_454';
    
    const IMAGE_PRESET_imagecache_3000_2000 = '3000_2000';
    const IMAGE_PRESET_imagecache_plain = 'plain';
    const IMAGE_PRESET_UPLOAD = 'upload';

    public static function processImageByPreset(\Imagine\Image\ImageInterface $imageObject, $presetName)
    {
        switch ($presetName) {
            case self::IMAGE_PRESET_604_331:

                $imageSize = $imageObject->getSize();
                $thumbnail = $imageObject->copy();
                $size = new \Imagine\Image\Box(604, 331);

                $ratios = array(
                    $size->getWidth() / $imageSize->getWidth(),
                    $size->getHeight() / $imageSize->getHeight()
                );
                $ratio = max($ratios);

                $imageSize = $thumbnail->getSize()->scale($ratio);
                $thumbnail->resize($imageSize);

                $result = $thumbnail->crop(new \Imagine\Image\Point(
                    max(0, round(($imageSize->getWidth() - $size->getWidth()) / 2)),
                    max(0, round(($imageSize->getHeight() - $size->getHeight()) / 2))
                ), $size);

                return $result;
                break;
            case self::IMAGE_PRESET_219_143:
                return $imageObject->thumbnail(new \Imagine\Image\Box(219, 143), \Imagine\Image\ImageInterface::THUMBNAIL_OUTBOUND);
                break;
            case self::IMAGE_PRESET_260_170:
                return $imageObject->thumbnail(new \Imagine\Image\Box(260, 170), \Imagine\Image\ImageInterface::THUMBNAIL_OUTBOUND);
                break;
            case self::IMAGE_PRESET_131_91:
                return $imageObject->thumbnail(new \Imagine\Image\Box(131, 91), \Imagine\Image\ImageInterface::THUMBNAIL_OUTBOUND);
                break;
            case self::IMAGE_PRESET_305_305:
                return $imageObject->thumbnail(new \Imagine\Image\Box(305, 305), \Imagine\Image\ImageInterface::THUMBNAIL_OUTBOUND);
                break;
            case self::IMAGE_PRESET_510_390:
                $imageSize = $imageObject->getSize();
                $thumbnail = $imageObject->copy();
                $size = new \Imagine\Image\Box(510, 390);

                $ratios = array(
                    $size->getWidth() / $imageSize->getWidth(),
                    $size->getHeight() / $imageSize->getHeight()
                );
                $ratio = max($ratios);

                $imageSize = $thumbnail->getSize()->scale($ratio);
                $thumbnail->resize($imageSize);

                $result = $thumbnail->crop(new \Imagine\Image\Point(
                    max(0, round(($imageSize->getWidth() - $size->getWidth()) / 2)),
                    max(0, round(($imageSize->getHeight() - $size->getHeight()) / 2))
                ), $size);

                return $result;
                break;

            case self::IMAGE_PRESET_800_600:
                return $imageObject->thumbnail(new \Imagine\Image\Box(800, 600), \Imagine\Image\ImageInterface::THUMBNAIL_OUTBOUND);
                break;
            case self::IMAGE_PRESET_622_392:
                return $imageObject->thumbnail(new \Imagine\Image\Box(622, 392), \Imagine\Image\ImageInterface::THUMBNAIL_INSET);
                break;
            case self::IMAGE_PRESET_160_200:
                return $imageObject->thumbnail(new \Imagine\Image\Box(160, 200), \Imagine\Image\ImageInterface::THUMBNAIL_OUTBOUND);
                break;
            case self::IMAGE_PRESET_160_90:
                return $imageObject->thumbnail(new \Imagine\Image\Box(160, 90), \Imagine\Image\ImageInterface::THUMBNAIL_OUTBOUND);
                break;
            case self::IMAGE_PRESET_128_75:
                return $imageObject->thumbnail(new \Imagine\Image\Box(128, 75), \Imagine\Image\ImageInterface::THUMBNAIL_OUTBOUND);
                break;
            case self::IMAGE_PRESET_280_317:
                return $imageObject->thumbnail(new \Imagine\Image\Box(280, 317), \Imagine\Image\ImageInterface::THUMBNAIL_OUTBOUND);
                break;
            case self::IMAGE_PRESET_38_30:
                return $imageObject->thumbnail(new \Imagine\Image\Box(38, 30), \Imagine\Image\ImageInterface::THUMBNAIL_INSET);
                break;
            case '40_40':
                return $imageObject->thumbnail(new \Imagine\Image\Box(40, 40), \Imagine\Image\ImageInterface::THUMBNAIL_OUTBOUND);
                break;
            case '138_138':
                return $imageObject->thumbnail(new \Imagine\Image\Box(138, 138), \Imagine\Image\ImageInterface::THUMBNAIL_OUTBOUND);
                break;
            case self::IMAGE_PRESET_120_120_auto:
                return $imageObject->thumbnail(new \Imagine\Image\Box(120, 120), \Imagine\Image\ImageInterface::THUMBNAIL_INSET);
                break;
            case self::IMAGE_PRESET_460_300_auto:

                return $imageObject->thumbnail(new \Imagine\Image\Box(460, 300), \Imagine\Image\ImageInterface::THUMBNAIL_INSET);
                break;
            case self::IMAGE_PRESET_460_320:
                return $imageObject->thumbnail(new \Imagine\Image\Box(460, 320), \Imagine\Image\ImageInterface::THUMBNAIL_INSET);
                break;
            case self::IMAGE_PRESET_800_800_auto:

                return $imageObject->thumbnail(new \Imagine\Image\Box(800, 800), \Imagine\Image\ImageInterface::THUMBNAIL_INSET);
                break;
            case self::IMAGE_PRESET_UPLOAD:
            case self::IMAGE_PRESET_imagecache_3000_2000:
            case self::IMAGE_PRESET_imagecache_plain:
                return $imageObject->thumbnail(new \Imagine\Image\Box(2000, 2000));
                break;
            case self::IMAGE_PRESET_FOTOBANK_263_263_auto:
                return $imageObject->thumbnail(new \Imagine\Image\Box(263, 263), \Imagine\Image\ImageInterface::THUMBNAIL_INSET);
                break;

            case self::IMAGE_PRESET_50_50:
                return $imageObject->thumbnail(new \Imagine\Image\Box(50, 50), \Imagine\Image\ImageInterface::THUMBNAIL_OUTBOUND);
                break;

            case self::IMAGE_PRESET_212_120:
                return $imageObject->thumbnail(new \Imagine\Image\Box(212, 120), \Imagine\Image\ImageInterface::THUMBNAIL_OUTBOUND);
                break;

            case self::IMAGE_PRESET_109_90:
                return $imageObject->thumbnail(new \Imagine\Image\Box(109, 90), \Imagine\Image\ImageInterface::THUMBNAIL_OUTBOUND);
                break;

            case self::IMAGE_PRESET_500_280:
                return $imageObject->thumbnail(new \Imagine\Image\Box(500, 280), \Imagine\Image\ImageInterface::THUMBNAIL_OUTBOUND);
                break;

            case self::IMAGE_PRESET_500_326:
                return $imageObject->thumbnail(new \Imagine\Image\Box(500, 326), \Imagine\Image\ImageInterface::THUMBNAIL_OUTBOUND);
                break;

            case self::IMAGE_PRESET_970_540:
                return $imageObject->thumbnail(new \Imagine\Image\Box(970, 540), \Imagine\Image\ImageInterface::THUMBNAIL_OUTBOUND);
                break;

            case self::IMAGE_PRESET_auto_129:
                return $imageObject->thumbnail(new \Imagine\Image\Box(2000, 129), \Imagine\Image\ImageInterface::THUMBNAIL_INSET);
                break;

            case self::IMAGE_PRESET_690_auto:
                return $imageObject->thumbnail(new \Imagine\Image\Box(690, 2000), \Imagine\Image\ImageInterface::THUMBNAIL_INSET);
                break;

            case self::IMAGE_PRESET_280_175:
                return $imageObject->thumbnail(new \Imagine\Image\Box(280, 175), \Imagine\Image\ImageInterface::THUMBNAIL_OUTBOUND);
                break;

            case self::IMAGE_PRESET_480_270:
                return $imageObject->thumbnail(new \Imagine\Image\Box(480, 270), \Imagine\Image\ImageInterface::THUMBNAIL_OUTBOUND);
                break;

            case self::IMAGE_PRESET_1024_576:
                return $imageObject->thumbnail(new \Imagine\Image\Box(1024, 576), \Imagine\Image\ImageInterface::THUMBNAIL_OUTBOUND);
                break;

            case self::IMAGE_PRESET_1024_512:
                return $imageObject->thumbnail(new \Imagine\Image\Box(1024, 512), \Imagine\Image\ImageInterface::THUMBNAIL_INSET);
                break;

            case self::IMAGE_PRESET_450_350_auto:
                return $imageObject->thumbnail(new \Imagine\Image\Box(450, 350), \Imagine\Image\ImageInterface::THUMBNAIL_INSET);
                break;
            case self::IMAGE_PRESET_33_24_auto:
                return $imageObject->thumbnail(new \Imagine\Image\Box(33, 24), \Imagine\Image\ImageInterface::THUMBNAIL_INSET);
                break;
            case self::IMAGE_PRESET_100_75_auto:
                return $imageObject->thumbnail(new \Imagine\Image\Box(100, 75), \Imagine\Image\ImageInterface::THUMBNAIL_INSET);
                break;
            case self::IMAGE_PRESET_149_149:
                return $imageObject->thumbnail(new \Imagine\Image\Box(149, 149), \Imagine\Image\ImageInterface::THUMBNAIL_OUTBOUND);
                break;
            case self::IMAGE_PRESET_944_auto:
                return $imageObject->thumbnail(new \Imagine\Image\Box(944, 2000), \Imagine\Image\ImageInterface::THUMBNAIL_INSET);
                break;
            case self::IMAGE_PRESET_960_auto:
            case self::IMAGE_PRESET_imagecache_960xauto:
                return $imageObject->thumbnail(new \Imagine\Image\Box(960, 2000), \Imagine\Image\ImageInterface::THUMBNAIL_INSET);
                break;
            case self::IMAGE_PRESET_150_150_auto:
                return $imageObject->thumbnail(new \Imagine\Image\Box(150, 150), \Imagine\Image\ImageInterface::THUMBNAIL_INSET);
                break;
            case self::IMAGE_PRESET_144_81:
            case self::IMAGE_PRESET_imagecache_144x81:
                return $imageObject->thumbnail(new \Imagine\Image\Box(144, 81), \Imagine\Image\ImageInterface::THUMBNAIL_OUTBOUND);
                break;
            case self::IMAGE_PRESET_105_auto:
                return $imageObject->thumbnail(new \Imagine\Image\Box(105, 2000), \Imagine\Image\ImageInterface::THUMBNAIL_INSET);
                break;
            case self::IMAGE_PRESET_96_96_auto:
                return $imageObject->thumbnail(new \Imagine\Image\Box(96, 96), \Imagine\Image\ImageInterface::THUMBNAIL_INSET);
                break;
            case self::IMAGE_PRESET_335_224:
                return $imageObject->thumbnail(new \Imagine\Image\Box(335, 224), \Imagine\Image\ImageInterface::THUMBNAIL_INSET);
                break;
            case self::IMAGE_PRESET_58_68_auto:
                return $imageObject->thumbnail(new \Imagine\Image\Box(58, 68), \Imagine\Image\ImageInterface::THUMBNAIL_INSET);
                break;
            case self::IMAGE_PRESET_26_26_auto:
                return $imageObject->thumbnail(new \Imagine\Image\Box(26, 26), \Imagine\Image\ImageInterface::THUMBNAIL_INSET);
                break;
            case self::IMAGE_PRESET_115_68:
                return $imageObject->thumbnail(new \Imagine\Image\Box(115, 68), \Imagine\Image\ImageInterface::THUMBNAIL_OUTBOUND);
                break;
            case self::IMAGE_PRESET_140_150_auto:
                return $imageObject->thumbnail(new \Imagine\Image\Box(140, 150), \Imagine\Image\ImageInterface::THUMBNAIL_INSET);
                break;
            case self::IMAGE_PRESET_144_68:
                return $imageObject->thumbnail(new \Imagine\Image\Box(144, 68), \Imagine\Image\ImageInterface::THUMBNAIL_OUTBOUND);
                break;
            case self::IMAGE_PRESET_140_140_auto:
                return $imageObject->thumbnail(new \Imagine\Image\Box(140, 140), \Imagine\Image\ImageInterface::THUMBNAIL_INSET);
                break;
            case self::IMAGE_PRESET_450_253:
            case self::IMAGE_PRESET_450x253:
                return $imageObject->thumbnail(new \Imagine\Image\Box(450, 253), \Imagine\Image\ImageInterface::THUMBNAIL_INSET);
                break;
            case self::IMAGE_PRESET_450_253_auto:
                return $imageObject->thumbnail(new \Imagine\Image\Box(450, 253), \Imagine\Image\ImageInterface::THUMBNAIL_INSET);
                break;
            case self::IMAGE_PRESET_270_152:
                return $imageObject->thumbnail(new \Imagine\Image\Box(270, 152), \Imagine\Image\ImageInterface::THUMBNAIL_INSET);
                break;
            case self::IMAGE_PRESET_270_152_outbound:
                return $imageObject->thumbnail(new \Imagine\Image\Box(270, 152), \Imagine\Image\ImageInterface::THUMBNAIL_OUTBOUND);
                break;
            case self::IMAGE_PRESET_300_160:
                return $imageObject->thumbnail(new \Imagine\Image\Box(300, 160), \Imagine\Image\ImageInterface::THUMBNAIL_INSET);
                break;
            case self::IMAGE_PRESET_380_180:
                return $imageObject->thumbnail(new \Imagine\Image\Box(380, 180), \Imagine\Image\ImageInterface::THUMBNAIL_INSET);
                break;
            case self::IMAGE_PRESET_940_430:
                return $imageObject->thumbnail(new \Imagine\Image\Box(940, 430), \Imagine\Image\ImageInterface::THUMBNAIL_INSET);
                break;
            case self::IMAGE_PRESET_auto_350:
                return $imageObject->thumbnail(new \Imagine\Image\Box(2000, 350), \Imagine\Image\ImageInterface::THUMBNAIL_INSET);
                break;
            case self::IMAGE_PRESET_232_129:
                return $imageObject->thumbnail(new \Imagine\Image\Box(232, 129), \Imagine\Image\ImageInterface::THUMBNAIL_INSET);
                break;
            case self::IMAGE_PRESET_940_600:
                return $imageObject->thumbnail(new \Imagine\Image\Box(940, 600), \Imagine\Image\ImageInterface::THUMBNAIL_INSET);
                break;
            case self::IMAGE_PRESET_640_360:
                return $imageObject->thumbnail(new \Imagine\Image\Box(640, 360), \Imagine\Image\ImageInterface::THUMBNAIL_OUTBOUND);
                break;
            case self::IMAGE_PRESET_630_420:
                return $imageObject->thumbnail(new \Imagine\Image\Box(630, 420), \Imagine\Image\ImageInterface::THUMBNAIL_OUTBOUND);
                break;
            case self::IMAGE_PRESET_690_388:
                return $imageObject->thumbnail(new \Imagine\Image\Box(690, 388), \Imagine\Image\ImageInterface::THUMBNAIL_OUTBOUND);
                break;
            case self::IMAGE_PRESET_390_260:
                return $imageObject->thumbnail(new \Imagine\Image\Box(390, 260), \Imagine\Image\ImageInterface::THUMBNAIL_OUTBOUND);
                break;

            case self::IMAGE_PRESET_320_240:
                return $imageObject->thumbnail(new \Imagine\Image\Box(320, 240), \Imagine\Image\ImageInterface::THUMBNAIL_OUTBOUND);
                break;
            case self::IMAGE_PRESET_240_135:
                return $imageObject->thumbnail(new \Imagine\Image\Box(240, 135), \Imagine\Image\ImageInterface::THUMBNAIL_OUTBOUND);
                break;
            case self::IMAGE_PRESET_SMARTTV_330_186:
                return $imageObject->thumbnail(new \Imagine\Image\Box(330, 186), \Imagine\Image\ImageInterface::THUMBNAIL_INSET);
                break;
            case self::IMAGE_PRESET_SMARTTV_330_205:
                return $imageObject->thumbnail(new \Imagine\Image\Box(330, 205), \Imagine\Image\ImageInterface::THUMBNAIL_INSET);
                break;

            case self::IMAGE_PRESET_120_90:
                return $imageObject->thumbnail(new \Imagine\Image\Box(120, 90), \Imagine\Image\ImageInterface::THUMBNAIL_OUTBOUND);
                break;
            case self::IMAGE_PRESET_190x160:
                return $imageObject->thumbnail(new \Imagine\Image\Box(190, 160), \Imagine\Image\ImageInterface::THUMBNAIL_OUTBOUND);
                break;
            case self::IMAGE_PRESET_90x90:
                return $imageObject->thumbnail(new \Imagine\Image\Box(90, 90), \Imagine\Image\ImageInterface::THUMBNAIL_OUTBOUND);
                break;
            case self::IMAGE_PRESET_81_81:
                return $imageObject->thumbnail(new \Imagine\Image\Box(81, 81), \Imagine\Image\ImageInterface::THUMBNAIL_OUTBOUND);
                break;
            case self::IMAGE_PRESET_230_auto:
                return $imageObject->thumbnail(new \Imagine\Image\Box(230, 2000), \Imagine\Image\ImageInterface::THUMBNAIL_INSET);
                break;
            case self::IMAGE_PRESET_190_auto:
                return $imageObject->thumbnail(new \Imagine\Image\Box(190, 2000), \Imagine\Image\ImageInterface::THUMBNAIL_INSET);
                break;
            case self::IMAGE_PRESET_320x180:
                return $imageObject->thumbnail(new \Imagine\Image\Box(320, 180), \Imagine\Image\ImageInterface::THUMBNAIL_OUTBOUND);
                break;
            case self::IMAGE_PRESET_300_auto:
                return $imageObject->thumbnail(new \Imagine\Image\Box(300, 2000), \Imagine\Image\ImageInterface::THUMBNAIL_INSET);
                break;
            case self::IMAGE_PRESET_100_56:
                return $imageObject->thumbnail(new \Imagine\Image\Box(100, 56), \Imagine\Image\ImageInterface::THUMBNAIL_OUTBOUND);
                break;
            case self::IMAGE_PRESET_220_120:
                return $imageObject->thumbnail(new \Imagine\Image\Box(220, 120), \Imagine\Image\ImageInterface::THUMBNAIL_OUTBOUND);
                break;
            case self::IMAGE_PRESET_440_245:
                return $imageObject->thumbnail(new \Imagine\Image\Box(440, 245), \Imagine\Image\ImageInterface::THUMBNAIL_OUTBOUND);
                break;
            case self::IMAGE_PRESET_300_215:
                return $imageObject->thumbnail(new \Imagine\Image\Box(300, 215), \Imagine\Image\ImageInterface::THUMBNAIL_OUTBOUND);
                break;
            case self::IMAGE_PRESET_286_140:
                return $imageObject->thumbnail(new \Imagine\Image\Box(286, 140), \Imagine\Image\ImageInterface::THUMBNAIL_OUTBOUND);
                break;
            case self::IMAGE_PRESET_SOCIALS:
                return $imageObject->thumbnail(new \Imagine\Image\Box(400, 400), \Imagine\Image\ImageInterface::THUMBNAIL_OUTBOUND);
                break;
            case self::IMAGE_PRESET_317_200:
                return $imageObject->thumbnail(new \Imagine\Image\Box(317, 200), \Imagine\Image\ImageInterface::THUMBNAIL_OUTBOUND);
                break;
            case self::IMAGE_PRESET_auto_150:
                return $imageObject->thumbnail(new \Imagine\Image\Box(2000, 150), \Imagine\Image\ImageInterface::THUMBNAIL_INSET);
                break;
            case self::IMAGE_PRESET_317_115:
                return $imageObject->thumbnail(new \Imagine\Image\Box(317, 115), \Imagine\Image\ImageInterface::THUMBNAIL_OUTBOUND);
                break;
            case self::IMAGE_PRESET_150_80:
                return $imageObject->thumbnail(new \Imagine\Image\Box(150, 80), \Imagine\Image\ImageInterface::THUMBNAIL_OUTBOUND);
                break;
            case self::IMAGE_PRESET_444_250:
                return $imageObject->thumbnail(new \Imagine\Image\Box(444, 250), \Imagine\Image\ImageInterface::THUMBNAIL_OUTBOUND);
                break;
            case self::IMAGE_PRESET_auto_53:
                return $imageObject->thumbnail(new \Imagine\Image\Box(2000, 53), \Imagine\Image\ImageInterface::THUMBNAIL_INSET);
                break;
            case self::IMAGE_PRESET_550x400:
                return $imageObject->thumbnail(new \Imagine\Image\Box(550, 400), \Imagine\Image\ImageInterface::THUMBNAIL_INSET);
                break;
            case self::IMAGE_PRESET_550_400_OUT:
                return $imageObject->thumbnail(new \Imagine\Image\Box(550, 400), \Imagine\Image\ImageInterface::THUMBNAIL_OUTBOUND);
                break;
            case self::IMAGE_PRESET_720_450:
                return $imageObject->thumbnail(new \Imagine\Image\Box(720, 450), \Imagine\Image\ImageInterface::THUMBNAIL_INSET);
                break;
            case self::IMAGE_PRESET_720_450_crop:

                $imageSize = $imageObject->getSize();
                $thumbnail = $imageObject->copy();
                $size = new \Imagine\Image\Box(720, 450);

                $ratios = array(
                    $size->getWidth() / $imageSize->getWidth(),
                    $size->getHeight() / $imageSize->getHeight()
                );
                $ratio = max($ratios);

                $imageSize = $thumbnail->getSize()->scale($ratio);
                $thumbnail->resize($imageSize);

                $result = $thumbnail->crop(new \Imagine\Image\Point(
                    max(0, round(($imageSize->getWidth() - $size->getWidth()) / 2)),
                    max(0, round(($imageSize->getHeight() - $size->getHeight()) / 2))
                ), $size);

                return $result;

                break;
            case self::IMAGE_PRESET_720_260:
                return $imageObject->thumbnail(new \Imagine\Image\Box(720, 260), \Imagine\Image\ImageInterface::THUMBNAIL_OUTBOUND);
                break;
            case self::IMAGE_PRESET_400_400_crop:

                $imageSize = $imageObject->getSize();
                $thumbnail = $imageObject->copy();
                $size = new \Imagine\Image\Box(400, 400);

                $ratios = array(
                    $size->getWidth() / $imageSize->getWidth(),
                    $size->getHeight() / $imageSize->getHeight()
                );
                $ratio = max($ratios);

                $imageSize = $thumbnail->getSize()->scale($ratio);
                $thumbnail->resize($imageSize);

                $result = $thumbnail->crop(new \Imagine\Image\Point(
                    max(0, round(($imageSize->getWidth() - $size->getWidth()) / 2)),
                    max(0, round(($imageSize->getHeight() - $size->getHeight()) / 2))
                ), $size);

                return $result;


                break;
            case self::IMAGE_PRESET_680_454:

                $imageSize = $imageObject->getSize();
                $thumbnail = $imageObject->copy();
                $size = new \Imagine\Image\Box(680, 454);

                $ratios = array(
                    $size->getWidth() / $imageSize->getWidth(),
                    $size->getHeight() / $imageSize->getHeight()
                );
                $ratio = max($ratios);

                $imageSize = $thumbnail->getSize()->scale($ratio);
                $thumbnail->resize($imageSize);

                $result = $thumbnail->crop(new \Imagine\Image\Point(
                    max(0, round(($imageSize->getWidth() - $size->getWidth()) / 2)),
                    max(0, round(($imageSize->getHeight() - $size->getHeight()) / 2))
                ), $size);

                return $result;


                break;

            case self::IMAGE_PRESET_453_255_crop:

                $imageSize = $imageObject->getSize();
                $thumbnail = $imageObject->copy();
                $size = new \Imagine\Image\Box(453, 255);

                $ratios = array(
                    $size->getWidth() / $imageSize->getWidth(),
                    $size->getHeight() / $imageSize->getHeight()
                );
                $ratio = max($ratios);

                $imageSize = $thumbnail->getSize()->scale($ratio);
                $thumbnail->resize($imageSize);

                $result = $thumbnail->crop(new \Imagine\Image\Point(
                    max(0, round(($imageSize->getWidth() - $size->getWidth()) / 2)),
                    max(0, round(($imageSize->getHeight() - $size->getHeight()) / 2))
                ), $size);

                return $result;

                break;

            case self::IMAGE_PRESET_372_247_crop:

                $imageSize = $imageObject->getSize();
                $thumbnail = $imageObject->copy();
                $size = new \Imagine\Image\Box(372, 247);

                $ratios = array(
                    $size->getWidth() / $imageSize->getWidth(),
                    $size->getHeight() / $imageSize->getHeight()
                );
                $ratio = max($ratios);

                $imageSize = $thumbnail->getSize()->scale($ratio);
                $thumbnail->resize($imageSize);

                $result = $thumbnail->crop(new \Imagine\Image\Point(
                    max(0, round(($imageSize->getWidth() - $size->getWidth()) / 2)),
                    max(0, round(($imageSize->getHeight() - $size->getHeight()) / 2))
                ), $size);

                return $result;

                break;


            default:
                error_log('Preset "' . $presetName . '" is not set');
                \OLOG\Exits::exit404();
        }
    }
}
