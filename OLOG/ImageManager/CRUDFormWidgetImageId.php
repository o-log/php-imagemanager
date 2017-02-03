<?php

namespace OLOG\ImageManager;

use OLOG\BT\BT;
use OLOG\CRUD\CRUDFieldsAccess;
use OLOG\CRUD\InterfaceCRUDFormWidget;
use OLOG\HTML;
use OLOG\Image\Image;
use OLOG\Image\Pages\Admin\ImageEditAction;
use OLOG\ImageManager\Presets\Preset320x240;
use OLOG\Preloader;
use OLOG\Sanitize;

class CRUDFormWidgetImageId implements InterfaceCRUDFormWidget
{
    protected $field_name;
    protected $field_value;
    protected $ajax_action_url;
    protected $is_required;
    protected $preset_class;
    protected $selector;

    public function __construct($field_name, $ajax_action_url = null, $is_required = false, $preset_class = Preset320x240::class)
    {
        $this->setFieldName($field_name);
        $this->setAjaxActionUrl($ajax_action_url);
        $this->setIsRequired($is_required);
        $this->setPresetClass($preset_class);
        $this->setSelector(uniqid('js_select_'));
    }

    public function html($obj)
    {
        $this->setFieldValue(CRUDFieldsAccess::getObjectFieldValue($obj, $this->getFieldName()));

        $html = '';

        # Form
        $html .= HTML::tag('div', ['class' => 'input-group'], function () {
            if ($this->getAjaxActionUrl()) {
                HTML::echoTag('span', ['class' => 'input-group-btn'], function () {
                    HTML::echoTag('button', [
                        'type' => 'button',
                        'class' => 'btn btn-default',
                        'id' => $this->getSelector() . '_choose_btn'
                    ], '<span class="glyphicon glyphicon-folder-open"></span>');
                });
            }

            HTML::echoTag('span', ['class' => 'input-group-btn'], function () {
                HTML::echoTag('button', [
                    'type' => 'button',
                    'id' => $this->getSelector() . '_btn_is_null',
                    'class' => 'btn btn-default'
                ], '<span class="glyphicon glyphicon-remove"></span>');
            });

            HTML::echoTag('input', [
                'type' => 'hidden',
                'id' => $this->getSelector() . '_is_null',
                'name' => $this->getFieldName() . '___is_null',
                'value' => (is_null($this->getFieldValue()) ? '1' : '')
            ], '');

            HTML::echoTag('input', [
                ($this->getIsRequired() ? 'required' : 'no-required') => 'true',
                'readonly' => 'true',
                'type' => 'input',
                'class' => 'form-control',
                'id' => $this->getSelector(),
                'name' => $this->getFieldName(),
                'value' => $this->getFieldValue()
            ], '');

            HTML::echoTag('span', ['class' => 'input-group-btn'], function () {
                HTML::echoTag('button', [
                    (!is_null($this->getFieldValue()) ? 'no-disabled' : 'disabled') => 'true',
                    'type' => 'button',
                    'id' => $this->getSelector() . '_btn_link',
                    'class' => 'btn btn-default'
                ], 'Перейти');
            });
        });

        # Preview
        $html .= HTML::tag('div', ['id' => $this->getSelector() . '_img_box'], function () {
            if ($this->getFieldValue()) {
                $image_obj = \OLOG\Image\Image::factory($this->getFieldValue(), false);
                if ($image_obj) {
                    $image_url = $image_obj->getImageUrlByPreset($this->getPresetClass());
                    if ($image_url != '') {
                        HTML::echoTag('img', [
                            'style' => 'margin-top: 15px;',
                            'id' => $this->getSelector() . '_img',
                            'src' => $image_url
                        ], '');
                    }
                }
            }
        });

        # Popup
        $html .= BT::modal($this->getSelector() . '_choose_form', 'Выбрать');

        ob_start(); ?>

        <?= Preloader::preloaderJsHtml() ?>

		<script>
            var CRUDFormWidgetImageId = function (selector) {

                var self = this;

                this.elemsSelectors = {
                    select_element:     '#' + selector,
                    choose_form:        '#' + selector + '_choose_form',
                    choose_btn:         '#' + selector + '_choose_btn',
                    button_link:        '#' + selector + '_btn_link',
                    input_is_null:      '#' + selector + '_is_null',
                    button_is_null:     '#' + selector + '_btn_is_null',
                    preview_img:        '#' + selector + '_img',
                    preview_img_box:    '#' + selector + '_img_box'
                };

                this.modal = function () {
                    $(this.elemsSelectors.choose_btn).on('click', function (e) {
                        e.preventDefault();

                        $.ajax({
                            url: '<?= $this->getAjaxActionUrl() ?>',
                            method: 'GET',
                            beforeSend: function () {
                                OLOG.preloader.show();
                            },
                            complete: function () {
                                OLOG.preloader.hide();
                            },
                            error: function (XMLHttpRequest, textStatus, errorThrown) {
                                console.log(XMLHttpRequest, textStatus, errorThrown);
                            },
                            success: function (responce_html) {
                                $(self.elemsSelectors.choose_form).modal("show");
                                $(self.elemsSelectors.choose_form).find('.modal-body').html(responce_html);
                            }
                        });
                    });
                    $(this.elemsSelectors.choose_form).on('hidden.bs.modal', function (e) {
                        $(this).find('.modal-body').html('');
                    });
                    $(this.elemsSelectors.choose_form).on('click', '.js-ajax-form-select', function (e) {
                        e.preventDefault();
                        $(self.elemsSelectors.choose_form).modal('hide');
                        $(self.elemsSelectors.button_link).attr('disabled', false);
                        $(self.elemsSelectors.select_element).val($(this).data('id')).trigger('change');
                        $(self.elemsSelectors.input_is_null).val('');
                    });
                };

                this.input = function () {
                    $(this.elemsSelectors.select_element).on('change', function () {
                        if ($(this).val() == '') {
                            $(self.elemsSelectors.input_is_null).val('1');
                        } else {
                            $(self.elemsSelectors.input_is_null).val('');
                        }

                        self.previewImages();
                    });
                };

                this.isNull = function () {
                    $(this.elemsSelectors.button_is_null).on('click', function (e) {
                        e.preventDefault();
                        $(self.elemsSelectors.select_element).val('').trigger('change');
                        $(self.elemsSelectors.button_link).attr('disabled', true);
                        $(self.elemsSelectors.input_is_null).val(1);
                        $(self.elemsSelectors.preview_img).remove();
                    });
                };

                this.link = function () {
                    $(this.elemsSelectors.button_link).on('click', function (e) {
                        e.preventDefault();
                        var url = '<?= (new ImageEditAction('REFERENCED_ID'))->url() ?>';
                        url = url.replace('REFERENCED_ID', $(self.elemsSelectors.select_element).val());
                        window.location = url;
                    });
                };

                this.previewImages = function () {
                    var $image = $(this.elemsSelectors.preview_img);
                    if ($image.length == 0) {
                        $image = $('<img>', {
                            style: 'margin-top: 15px;',
                            id: this.elemsSelectors.preview_img
                        });
                        $(this.elemsSelectors.preview_img_box).html($image);
                    }

                    var image_id = $(this.elemsSelectors.select_element).val();
                    if (image_id != '') {
                        var url = ('<?= (new ImageRelativeUrlByImageIdAjaxAction('#IMAGE#', '#PRESET#'))->url() ?>').replace("#IMAGE#", image_id).replace("#PRESET#", '<?= ImageManager::getPresetAliasByClassName($this->getPresetClass()) ?>');

                        $.ajax({
                            url: url,
                            method: 'GET',
                            beforeSend: function () {
                                OLOG.preloader.show();
                            },
                            complete: function () {
                                OLOG.preloader.hide();
                            },
                            error: function (XMLHttpRequest, textStatus, errorThrown) {
                                console.log(XMLHttpRequest, textStatus, errorThrown);
                            },
                            success: function (responce) {
                                if (responce.success) {
                                    $image.attr('src', responce.image_path);
                                } else {
                                    alert('Картинки с таким ID не существует!');
                                }
                            }
                        });
                    }
                };

                // init
                this.modal();
                this.input();
                this.isNull();
                this.link();
            };
            new CRUDFormWidgetImageId('<?= $this->getSelector() ?>');
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
    public function getSelector()
    {
        return $this->selector;
    }

    /**
     * @param mixed $select_element_id
     */
    public function setSelector($selector)
    {
        $this->selector = $selector;
    }

}