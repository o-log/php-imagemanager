<?php

namespace OLOG\ImageManager;

use OLOG\Image\Image;
use OLOG\ImageManager\Presets\Preset320x240;
use OLOG\InterfaceAction;
use OLOG\Layouts\LayoutJSON;

class ImageRelativeUrlByImageIdAjaxAction implements InterfaceAction
{
	protected $image_id;
	protected $image_preset_alias;

	public function __construct($image_id, $image_preset_alias = null)
	{
		$this->image_id = $image_id;
		if (!$image_preset_alias) {
			$this->image_preset_alias = (new Preset320x240())->getAlias();
		} else {
			$this->image_preset_alias = $image_preset_alias;
		}
	}

	static public function urlMask()
	{
		return '/imagemanager_ajax/image_relative_url/(\d+)/?(.*)?';
	}

	public function url()
	{
		return '/imagemanager_ajax/image_relative_url/' . $this->image_id . '/' . $this->image_preset_alias;
	}

	public function action()
	{
		\OLOG\Exits::exit403If(!\OLOG\Auth\Operator::currentOperatorHasAnyOfPermissions([\OLOG\ImageManager\Permissions::PERMISSION_PHPIMAGEMANAGER_MANAGE_IMAGES]));

		$image_obj = Image::factory($this->image_id, false);
		if (is_null($image_obj)) {
			LayoutJSON::render(['success' => false], $this);
		}

		$preset_class_name = ImageManager::getPresetClassNameByAlias($this->image_preset_alias);
		\OLOG\Exits::exit404If(!$preset_class_name);

		$image_path = $image_obj->getImageUrlByPreset($preset_class_name);

		LayoutJSON::render([
			'success' => true,
			'image_path' => $image_path
		], $this);
	}
}