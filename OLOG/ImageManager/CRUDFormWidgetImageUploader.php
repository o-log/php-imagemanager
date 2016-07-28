<?php

namespace OLOG\ImageManager;

use OLOG\CRUD\CRUDFieldsAccess;
use OLOG\CRUD\InterfaceCRUDFormWidget;
use OLOG\Sanitize;

class CRUDFormWidgetImageUploader implements InterfaceCRUDFormWidget
{
    protected $file_path_in_storage_field_name;
    protected $storage_field_name;
    protected $action_url;
    protected $storages_arr;
    protected $force_jpeg_image_format;

    public function __construct($file_path_in_storage_field_name, $storage_field_name, $storages_arr, $action_url, $force_jpeg_image_format = true)
    {
        $this->setFilePathInStorageFieldName($file_path_in_storage_field_name);
        $this->setStorageFieldName($storage_field_name);
        $this->setActionUrl($action_url);
        $this->setStoragesArr($storages_arr);
        $this->setForceJpegImageFormat($force_jpeg_image_format);
    }

    /**
     * @return mixed
     */
    public function getStorageFieldName()
    {
        return $this->storage_field_name;
    }

    /**
     * @param mixed $storage_field_name
     */
    public function setStorageFieldName($storage_field_name)
    {
        $this->storage_field_name = $storage_field_name;
    }

    /**
     * @return mixed
     */
    public function getActionUrl()
    {
        return $this->action_url;
    }

    /**
     * @param mixed $action_url
     */
    public function setActionUrl($action_url)
    {
        $this->action_url = $action_url;
    }

    /**
     * @return array
     */
    public function getStoragesArr()
    {
        return $this->storages_arr;
    }

    /**
     * @param array $storages_arr
     */
    public function setStoragesArr($storages_arr)
    {
        $this->storages_arr = $storages_arr;
    }

    /**
     * @return mixed
     */
    public function getFilePathInStorageFieldName()
    {
        return $this->file_path_in_storage_field_name;
    }

    /**
     * @param mixed $file_path_in_storage_field_name
     */
    public function setFilePathInStorageFieldName($file_path_in_storage_field_name)
    {
        $this->file_path_in_storage_field_name = $file_path_in_storage_field_name;
    }

    /**
     * @return boolean
     */
    public function getForceJpegImageFormat()
    {
        return $this->force_jpeg_image_format;
    }

    /**
     * @param boolean $force_jpeg_image_format
     */
    public function setForceJpegImageFormat($force_jpeg_image_format)
    {
        $this->force_jpeg_image_format = $force_jpeg_image_format;
    }

    public function html($obj)
    {
        static $CRUDFormWidgetImageUploader_include_script;

        $storage_name_field_value = CRUDFieldsAccess::getObjectFieldValue($obj, $this->getStorageFieldName());
        $file_path_in_storage_field_value = CRUDFieldsAccess::getObjectFieldValue($obj, $this->getFilePathInStorageFieldName());

        ob_start();
        ?>

        <div class="row">
            <div class="col-sm-12">
                <div class="upload_form">
                    <input name="<?= Sanitize::sanitizeAttrValue($this->getStorageFieldName()) ?>" class="form-control"
                           readonly
                           value="<?= Sanitize::sanitizeAttrValue($storage_name_field_value) ?>">
                    <input name="<?= Sanitize::sanitizeAttrValue($this->getFilePathInStorageFieldName()) ?>"
                           class="form-control"
                           readonly value="<?= Sanitize::sanitizeAttrValue($file_path_in_storage_field_value) ?>">

                    <select name="upload_storage_name" class="form-control upload_storage_name_input"
                            onchange="onChange(this)">
                        <option></option>
                        <?php
                        foreach ($this->getStoragesArr() as $storage_name => $storage_id) {
                            echo '<option value="' . \OLOG\Sanitize::sanitizeAttrValue($storage_id) . '">' . \OLOG\Sanitize::sanitizeTagContent($storage_name) . '</option>';
                        }
                        ?>
                    </select>

                    <div class="input-group">
                        <input name="upload_image_file" type="file" class="form-control upload_image_file_input"
                               onchange="onChange(this)">
                        <span class="input-group-btn"><button class="btn btn-default upload_button" type="button"
                                                              onclick="processUpload(this,
                                                                  '<?= \OLOG\Sanitize::sanitizeUrl($this->getActionUrl()) ?>',
                                                                  '<?= Sanitize::sanitizeAttrValue($this->getStorageFieldName()) ?>',
                                                                  '<?= Sanitize::sanitizeAttrValue($this->getFilePathInStorageFieldName()) ?>',
                                                                  '<?= Sanitize::sanitizeAttrValue(intval($this->getForceJpegImageFormat())) ?>')"
                                                              disabled>Закачать</button></span>
                    </div>
                    <div class="alert alert-danger" role="alert" style="display: none"></div>
                    <div class="progress" style="display: none">
                        <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0"
                             aria-valuemax="100" style="width: 0;"></div>
                    </div>
                    <div class="uploaded_image">
                        <?php
                        if ($storage_name_field_value && $file_path_in_storage_field_value) {
                            $image_manager_obj = new ImageManager($storage_name_field_value);
                            echo '<img src="' . \OLOG\Sanitize::sanitizeUrl($image_manager_obj->getImageUrlByPreset($file_path_in_storage_field_value, ImageManagerConfig::getDefaultUploadPresetClassName())) . '" width="100%">';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>

        <?php if (!isset($CRUDFormWidgetImageUploader_include_script)) { ?>
        <script type="text/javascript">
            function onChange(input) {
                var disable_buttons = false;

                var $upload_form = $(input).closest('.upload_form');
                var storage_name = $(".upload_storage_name_input", $upload_form).val();
                var file_name = $(".upload_image_file_input", $upload_form).val();

                if ((storage_name == '') || (file_name == '')) {
                    disable_buttons = true;
                }

                var upload_button = $(".upload_button", $upload_form);
                upload_button.attr("disabled", disable_buttons);
            }

            function processUpload(btn, upload_url, storage_name_input_name, file_path_in_storage_input_name, force_jpeg_image_format) {
                var $upload_form = $(btn).closest('.upload_form');

                var storage_name = $(".upload_storage_name_input", $upload_form).val();
                if ((typeof storage_name == "undefined") || (storage_name == '')) {
                    return;
                }

                var upload_image_file = $(".upload_image_file_input", $upload_form)[0].files[0];
                if ((typeof upload_image_file == "undefined") || (upload_image_file == '')) {
                    return;
                }

                var form_data = new FormData();
                form_data.append("upload_storage_name", storage_name);
                form_data.append("<?= Sanitize::sanitizeAttrValue(\OLOG\ImageManager\ImageUploadAction::FORCE_JPEG_IMAGE_FORMAT_FIELD_NAME)?>", force_jpeg_image_format);
                form_data.append("upload_image_file", upload_image_file);

                var upload_button = $(".upload_button", $upload_form);
                upload_button.attr("disabled", true);

                var file_input = $(".upload_image_file_input", $upload_form);
                file_input.attr("disabled", true);

                var upload_errors = $(".alert", $upload_form);
                upload_errors.fadeOut();

                var progress_bar = $(".progress-bar", $upload_form);
                var progress_bar_div = $(".progress", $upload_form);
                progress_bar_div.fadeIn();

                $.ajax({
                    type: "post",
                    url: upload_url,
                    data: form_data,
                    processData: false,
                    contentType: false,
                    xhr: function () {
                        var xhr = new window.XMLHttpRequest();

                        xhr.upload.addEventListener("progress", function (evt) {
                            if (evt.lengthComputable) {
                                var percentage = Math.floor((evt.loaded / evt.total) * 100);
                                progress_bar.width(percentage + "%").attr("aria-valuenow", percentage);
                            }
                        }, false);

                        return xhr;
                    }
                }).done(function (data) {
                    upload_button.attr("disabled", false);
                    file_input.attr("disabled", false);
                    progress_bar_div.fadeOut();

                    if (!data.success) {
                        upload_errors.html(data.error_message);
                        upload_errors.fadeIn();
                        return;
                    }

                    $("input[name=" + file_path_in_storage_input_name + "]", $upload_form).val(data.file_path_in_storage);
                    $("input[name=" + storage_name_input_name + "]", $upload_form).val(data.storage_name);
                    $(".uploaded_image", $upload_form).html('<img width="100%" src="' + data.image_url + '">');
                }).fail(function () {
                    upload_button.attr("disabled", false);
                    file_input.attr("disabled", false);
                    progress_bar_div.fadeOut();

                    upload_errors.html('Ошибка сервера');
                    upload_errors.fadeIn();
                });
            }
        </script>
        <?php
    }
        $html = ob_get_clean();
        return $html;
    }
}