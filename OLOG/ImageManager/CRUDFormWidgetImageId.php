<?php

namespace OLOG\ImageManager;

use OLOG\BT\BT;
use OLOG\CRUD\CRUDFieldsAccess;
use OLOG\CRUD\InterfaceCRUDFormWidget;
use OLOG\HTML;
use OLOG\Image\Pages\Admin\ImageEditAction;
use OLOG\ImageManager\Presets\Preset320x240;
use OLOG\Preloader;

class CRUDFormWidgetImageId implements InterfaceCRUDFormWidget
{
    protected $field_name;
    protected $field_value;
    protected $ajax_action_url;
    protected $is_required;
    protected $preset_class;
    protected $select_element_id;
    protected $choose_form_element_id;

    public function __construct($field_name, $ajax_action_url = null, $is_required = false, $preset_class = Preset320x240::class)
    {
        $this->setFieldName($field_name);
        $this->setAjaxActionUrl($ajax_action_url);
        $this->setIsRequired($is_required);
        $this->setPresetClass($preset_class);
        $this->setSelectElementId(uniqid('js_select_'));
        $this->setChooseFormElementId(uniqid('collapse_'));
    }

    public function html($obj)
    {
        $this->setFieldValue(CRUDFieldsAccess::getObjectFieldValue($obj, $this->getFieldName()));

        $html = '';

        $html .= HTML::tag('div', ['class' => 'input-group'], function () {

            if ($this->getAjaxActionUrl()) {
                HTML::echoTag('span', ['class' => 'input-group-btn'], function () {
                    HTML::echoTag('button', [
                        'type' => 'button',
                        'class' => 'btn btn-default',
                        'data-toggle' => 'modal',
                        'data-target' => '#' . $this->getChooseFormElementId()
                    ], '<span class="glyphicon glyphicon-folder-open"></span>');
                });
            }

            HTML::echoTag('span', ['class' => 'input-group-btn'], function () {
                HTML::echoTag('button', [
                    'type' => 'button',
                    'id' => $this->getSelectElementId() . '_btn_is_null',
                    'class' => 'btn btn-default',
                    'data-toggle' => 'modal'
                ], '<span class="glyphicon glyphicon-remove"></span>');
            });

            HTML::echoTag('input', [
                'type' => 'hidden',
                'id' => $this->getSelectElementId() . '_is_null',
                'name' => $this->getFieldName() . '___is_null',
                'value' => (is_null($this->getFieldValue()) ? '1' : '')
            ], '');

            HTML::echoTag('input', [
                'readonly' => 'true',
                'type' => 'input',
                'class' => 'form-control',
                'id' => $this->getSelectElementId(),
                'name' => $this->getFieldName(),
                'value' => $this->getFieldValue(),
                'data-field' => $this->getSelectElementId() . '_text'
            ], '');

            HTML::echoTag('span', ['class' => 'input-group-btn'], function () {
                HTML::echoTag('button', [
                    (!is_null($this->getFieldValue()) ? 'no-disabled' : 'disabled') => 'true',
                    'type' => 'button',
                    'id' => $this->getSelectElementId() . '_btn_link',
                    'class' => 'btn btn-default',
                    'data-toggle' => 'modal'
                ], 'Перейти');
            });
        });

        $html .= HTML::tag('div', ['id' => $this->getSelectElementId() . '_img_box'], function () {
            if ($this->getFieldValue()) {
                $image_obj = \OLOG\Image\Image::factory($this->getFieldValue(), false);
                if ($image_obj) {
                    $image_url = $image_obj->getImageUrlByPreset($this->getPresetClass());
                    if ($image_url != '') {
                        HTML::echoTag('img', [
                            'style' => 'margin-top: 15px;',
                            'id' => $this->getSelectElementId() . '_img',
                            'src' => $image_url
                        ], '');
                    }
                }
            }
        });

        $html .= BT::modal($this->getChooseFormElementId(), 'Выбрать');

        ob_start(); ?>

        <?= Preloader::preloaderJsHtml() ?>

		<script>


            $('#<?= $this->getChooseFormElementId() ?>').on('hidden.bs.modal', function (e) {
                $('#<?= $this->getChooseFormElementId() ?> .modal-body').html('');
            });

            $('#<?= $this->getChooseFormElementId() ?>').on('shown.bs.modal', function (e) {
                OLOG.preloader.show();
                $.ajax({
                    url: "<?= $this->getAjaxActionUrl() ?>"
                }).success(function (received_html) {
                    $('#<?= $this->getChooseFormElementId() ?> .modal-body').html(received_html);
                    OLOG.preloader.hide();
                });
            });

            $('#<?= $this->getChooseFormElementId() ?>').on('click', '.js-ajax-form-select', function (e) {
                e.preventDefault();
                var select_id = $(this).data('id');
                var select_title = $(this).data('title');
                $('#<?= $this->getChooseFormElementId() ?>').modal('hide');
                $('#<?= $this->getSelectElementId() ?>_text').text(select_title);
                $('#<?= $this->getSelectElementId() ?>_btn_link').attr('disabled', false);
                $('#<?= $this->getSelectElementId() ?>').val(select_id).trigger('change');
                $('#<?= $this->getSelectElementId() ?>_is_null').val('');
            });

            $('#<?= $this->getSelectElementId() ?>_btn_is_null').on('click', function (e) {
                e.preventDefault();
                $('#<?= $this->getSelectElementId() ?>_text').text('');
                $('#<?= $this->getSelectElementId() ?>_btn_link').attr('disabled', true);
                $('#<?= $this->getSelectElementId() ?>').val('').trigger('change');
                $('#<?= $this->getSelectElementId() ?>_is_null').val(1);
                $('#<?= $this->getSelectElementId() ?>_img').remove();
            });

            $('#<?= $this->getSelectElementId() ?>_btn_link').on('click', function (e) {
                var url = '<?= (new ImageEditAction('REFERENCED_ID'))->url() ?>';
                var id = $('#<?= $this->getSelectElementId() ?>').val();
                url = url.replace('REFERENCED_ID', id);

                window.location = url;
            });
            var $input_is_null = $('#<?= $this->getSelectElementId() ?>_is_null');
            var $input = $('#<?= $this->getSelectElementId() ?>');
            $input.on('change', function () {
                if ($(this).val() == '') {
                    $input_is_null.val('1');
                } else {
                    $input_is_null.val('');
                }
            });

            $input.on('change', function () {
                var $input = $('#<?= $this->getSelectElementId() ?>');
                var $image = $('#<?= $this->getSelectElementId() ?>_img');
                var $image_box = $('#<?= $this->getSelectElementId() ?>_img_box');
                if ($image.length == 0) {
                    $image = $('<img style="margin-top: 15px;" id="<?= $this->getSelectElementId() ?>_img">');
                    $image_box.html($image);
                }
                var image_id = $input.val();
                if (image_id != '') {
                    OLOG.preloader.show();
                    var url = ('<?= (new ImageRelativeUrlByImageIdAjaxAction('#IMAGE#', '#PRESET#'))->url() ?>').replace("#IMAGE#/#PRESET#", "");

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
    public function getFieldValue()
    {
        return $this->field_value;
    }

    /**
     * @param mixed $field_value
     */
    public function setFieldValue($field_value)
    {
        $this->field_value = $field_value;
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

    /**
     * @return mixed
     */
    public function getIsRequired()
    {
        return $this->is_required;
    }

    /**
     * @param mixed $is_required
     */
    public function setIsRequired($is_required)
    {
        $this->is_required = $is_required;
    }

    /**
     * @return mixed
     */
    public function getPresetClass()
    {
        return $this->preset_class;
    }

    /**
     * @param mixed $preset_class
     */
    public function setPresetClass($preset_class)
    {
        $this->preset_class = $preset_class;
    }

    /**
     * @return mixed
     */
    public function getSelectElementId()
    {
        return $this->select_element_id;
    }

    /**
     * @param mixed $select_element_id
     */
    public function setSelectElementId($select_element_id)
    {
        $this->select_element_id = $select_element_id;
    }

    /**
     * @return mixed
     */
    public function getChooseFormElementId()
    {
        return $this->choose_form_element_id;
    }

    /**
     * @param mixed $choose_form_element_id
     */
    public function setChooseFormElementId($choose_form_element_id)
    {
        $this->choose_form_element_id = $choose_form_element_id;
    }

}