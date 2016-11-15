<?php

namespace OLOG\ImageManager;

use OLOG\GETAccess;
use OLOG\Image\Image;
use OLOG\ImageManager\Presets\Preset320x240;
use OLOG\InterfaceAction;
use OLOG\Layouts\LayoutJSON;

class ImagePathByImageIdAjaxAction implements InterfaceAction
{
	protected $image_id;

	public function __construct($image_id)
	{
		$this->image_id = $image_id;
	}

	static public function urlMask()
	{
		return '/imagemanager/image_path_ajax/(\d+)';
	}

	public function url()
	{
		return '/imagemanager/image_path_ajax/' . $this->image_id;
	}

	public function action()
	{
		\OLOG\Exits::exit403If(!\OLOG\Auth\Operator::currentOperatorHasAnyOfPermissions([\OLOG\ImageManager\Permissions::PERMISSION_PHPIMAGEMANAGER_MANAGE_IMAGES]));

		$image_obj = Image::factory($this->image_id, false);
		if (is_null($image_obj)) {
			LayoutJSON::render(['success' => false], $this);
		}

		$preset_class_name = urldecode(GETAccess::getOptionalGetValue('preset_class_name', Preset320x240::class));

		if (!class_exists($preset_class_name)) {
			$preset_class_name = Preset320x240::class;
		}

		$image_path = $image_obj->getImageUrlByPreset($preset_class_name);

		LayoutJSON::render([
			'success' => true,
			'image_path' => $image_path
		], $this);
	}
}