<?php

namespace OLOG\ImageManager;

use OLOG\BT\BT;
use OLOG\CRUD\CRUDFieldsAccess;
use OLOG\CRUD\InterfaceCRUDFormWidget;
use OLOG\Image\Image;
use OLOG\Image\Pages\Admin\ImageEditAction;
use OLOG\ImageManager\Presets\Preset320x240;
use OLOG\Preloader;
use OLOG\Sanitize;

class CRUDFormWidgetImageId implements InterfaceCRUDFormWidget
{
    protected $field_name;
    protected $ajax_action_url;

    public function __construct($field_name, $ajax_action_url = null)
    {
        $this->setFieldName($field_name);
        $this->setAjaxActionUrl($ajax_action_url);
    }

    public function html($obj)
    {
        $field_name = $this->getFieldName();
        $field_value = CRUDFieldsAccess::getObjectFieldValue($obj, $field_name);

        $disabled_btn_link = 'disabled';
        $is_null_value = '';

        if (is_null($field_value)){
            $is_null_value = "1";
        }

        if (!is_null($field_value)) {
            $referenced_image_obj = Image::factory($field_value);
            $disabled_btn_link = '';
        }

        $html = '';
	    $html .= Preloader::preloaderJsHtml();

        $select_element_id = 'js_select_' . rand(1, 999999);
        $choose_form_element_id = 'collapse_' . rand(1, 999999);

        $html .= '<div class="input-group">';

        if ($this->getAjaxActionUrl()) {
            $html .= '<span class="input-group-btn">';
            $html .= '<button type="button" class="btn btn-default" data-toggle="modal" data-target="#' . $choose_form_element_id . '"><span class="glyphicon glyphicon-folder-open"></span></button>';
            $html .= '</span>';
        }

        $html .= '<span class="input-group-btn">';
        $html .= '<button type="button" id="' . Sanitize::sanitizeAttrValue($select_element_id) . '_btn_is_null" class="btn btn-default" data-toggle="modal"><span class="glyphicon glyphicon-remove"></span></button>';
        $html .= '</span>';
        $html .= '<input type="hidden" id="' . Sanitize::sanitizeAttrValue($select_element_id) . '_is_null" name="' . Sanitize::sanitizeAttrValue($field_name) . '___is_null" value="' . $is_null_value . '"/>';
        $html .= '<input readonly type="input" id="' . Sanitize::sanitizeAttrValue($select_element_id) . '" name="' . Sanitize::sanitizeAttrValue($field_name) . '" class="form-control" value="' . $field_value . '" data-field="' . Sanitize::sanitizeAttrValue($select_element_id) . '_text"/>';

        //if ($this->getEditorUrl()) {
        $html .= '<span class="input-group-btn">';
        $html .= '<button ' . $disabled_btn_link . ' type="button" id="' . Sanitize::sanitizeAttrValue($select_element_id) . '_btn_link" class="btn btn-default" data-toggle="modal">Перейти</button>';
        $html .= '</span>';
        //}

        $html .= '</div>';

	    $html .= '<div id="' . Sanitize::sanitizeAttrValue($select_element_id) . '_img_box">';
        if ($field_value) {
            $image_obj = \OLOG\Image\Image::factory($field_value, false);
            if ($image_obj) {
                $image_url = $image_obj->getImageUrlByPreset(Preset320x240::class);
                if ($image_url != '') {
		            $html .= '<img style="margin-top: 15px;" id="' . Sanitize::sanitizeAttrValue($select_element_id) . '_img" src="' . \OLOG\Sanitize::sanitizeUrl($image_url) . '"/>';
                }
            }
        }
	    $html .= '</div>';

        $html .= BT::modal($choose_form_element_id, 'Выбрать');

        ob_start();?>

        <script>

	        $('#<?= $choose_form_element_id ?>').on('hidden.bs.modal', function () {
		        $('#<?= $choose_form_element_id ?> .modal-body').html('');
		    });

	        $('#<?= $choose_form_element_id ?>').on('shown.bs.modal', function (e) {
		        OLOG.preloader.show();
                $.ajax({
                    url: "<?= $this->getAjaxActionUrl() ?>"
                }).success(function(received_html) {
                    $('#<?= $choose_form_element_id ?> .modal-body').html(received_html);
	                OLOG.preloader.hide();
                });
            });

            $('#<?= $choose_form_element_id ?>').on('click', '.js-ajax-form-select', function (e) {
                e.preventDefault();
                var select_id = $(this).data('id');
                var select_title = $(this).data('title');
                $('#<?= $choose_form_element_id ?>').modal('hide');
                $('#<?= $select_element_id ?>_text').text(select_title);
                $('#<?= $select_element_id ?>_btn_link').attr('disabled', false);
                $('#<?= $select_element_id ?>').val(select_id).trigger('change');
                $('#<?= $select_element_id ?>_is_null').val('');
            });


            $('#<?= $select_element_id ?>_btn_is_null').on('click', function (e) {
                e.preventDefault();
                $('#<?= $select_element_id ?>_text').text('');
                $('#<?= $select_element_id ?>_btn_link').attr('disabled', true);
                $('#<?= $select_element_id ?>').val('').trigger('change');
                $('#<?= $select_element_id ?>_is_null').val(1);
                $('#<?= $select_element_id ?>_img').remove();
            });

            $('#<?= $select_element_id ?>_btn_link').on('click', function (e) {
                var url = '<?= (new ImageEditAction('REFERENCED_ID'))->url() ?>';
                var id = $('#<?= $select_element_id ?>').val();
                url = url.replace('REFERENCED_ID', id);

                window.location = url;
            });
            var $input_is_null = $('#<?= $select_element_id ?>_is_null');
            var $input = $('#<?= $select_element_id ?>');
            $input.on('change', function () {
                if ($(this).val() == '') {
                    $input_is_null.val('1');
                }else{
                    $input_is_null.val('');
                }
            });

	        $input.on('change', function () {
		        var $input = $('#<?= $select_element_id ?>');
		        var $image = $('#<?= $select_element_id ?>_img');
		        var $image_box = $('#<?= $select_element_id ?>_img_box');
		        if ($image.length == 0) {
			        $image = $('<img style="margin-top: 15px;" id="<?= $select_element_id ?>_img">');
			        $image_box.html($image);
		        }
		        var image_id = $input.val();
		        if (image_id != '') {
			        OLOG.preloader.show();
			        var url = ('<?= (new ImageRelativeUrlByImageIdAjaxAction('#IMAGE#', '#PRESET#'))->url() ?>').replace("#IMAGE#/#PRESET#","");

			        $.ajax(url + image_id)
				        .done(function (data) {
					        if (data.success) {
						        $image.attr('src', data.image_path).on('load', function () {
							        OLOG.preloader.hide();
						        });
					        } else {
						        OLOG.preloader.hide();
						        alert('Картинки с таким ID не существует!');
					        }
				        });
		        }
	        });
        </script>

        <?php
        $html .= ob_get_clean();

        return $html;
    }

    /**
     * @return mixed
     */
    public function getFieldName()
    {
        return $this->field_name;
    }

    /**
     * @param mixed $field_name
     */
    public function setFieldName($field_name)
    {
        $this->field_name = $field_name;
    }

    /**
     * @return mixed
     */
    public function getAjaxActionUrl()
    {
        return $this->ajax_action_url;
    }

    /**
     * @param mixed $ajax_action_url
     */
    public function setAjaxActionUrl($ajax_action_url)
    {
        $this->ajax_action_url = $ajax_action_url;
    }

}