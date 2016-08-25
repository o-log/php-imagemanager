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

        $file_input_id = uniqid();
        $upload_form_id = uniqid();

        ?>

        <div class="row">
            <div class="col-sm-12">
                <div class="upload_form" id="<?= $upload_form_id ?>">
                    <input style="display: none;" name="<?= Sanitize::sanitizeAttrValue($this->getStorageFieldName()) ?>" class="form-control" readonly value="<?= Sanitize::sanitizeAttrValue($storage_name_field_value) ?>">
                    <input style="display: none;" name="<?= Sanitize::sanitizeAttrValue($this->getFilePathInStorageFieldName()) ?>" class="form-control" readonly value="<?= Sanitize::sanitizeAttrValue($file_path_in_storage_field_value) ?>">

                    <?php

                    $storage_select_styles = '';
                    if (count($this->getStoragesArr()) == 1) {
                        $storage_select_styles = ' style="display: none;" ';
                    }

                    ?>
                    <select <?= $storage_select_styles ?> name="upload_storage_name" class="form-control upload_storage_name_input" onchange="fileFieldOnChange(this);">
                        <?php
                        // если возможно несколько стореждей картинок - не предлагаем никакой по умолчанию, чтобы не навязывать пользователю выбор. пусть выбирает сам
                        if (count($this->getStoragesArr()) != 1) {
                            echo '<option></option>';
                        }
                        foreach ($this->getStoragesArr() as $storage_name => $storage_id) {
                            echo '<option value="' . \OLOG\Sanitize::sanitizeAttrValue($storage_id) . '">' . \OLOG\Sanitize::sanitizeTagContent($storage_name) . '</option>';
                        }
                        ?>
                    </select>

                    <input name="upload_image_file" type="file" class="form-control upload_image_file_input" id="<?= $file_input_id ?>" onchange="fileFieldOnChange(this);">

                    <div class="alert alert-danger" role="alert" style="display: none"></div>
                    <div class="progress" style="display: none">
                        <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0;"></div>
                    </div>
                    <div class="uploaded_image" style="background: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAMAAAAp4XiDAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyNpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuNS1jMDE0IDc5LjE1MTQ4MSwgMjAxMy8wMy8xMy0xMjowOToxNSAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENDIChNYWNpbnRvc2gpIiB4bXBNTTpJbnN0YW5jZUlEPSJ4bXAuaWlkOjJFMDRDRjAzNUJDOTExRTZBN0RDQTRCMEZGRjBBQTgzIiB4bXBNTTpEb2N1bWVudElEPSJ4bXAuZGlkOjJFMDRDRjA0NUJDOTExRTZBN0RDQTRCMEZGRjBBQTgzIj4gPHhtcE1NOkRlcml2ZWRGcm9tIHN0UmVmOmluc3RhbmNlSUQ9InhtcC5paWQ6MkUwNENGMDE1QkM5MTFFNkE3RENBNEIwRkZGMEFBODMiIHN0UmVmOmRvY3VtZW50SUQ9InhtcC5kaWQ6MkUwNENGMDI1QkM5MTFFNkE3RENBNEIwRkZGMEFBODMiLz4gPC9yZGY6RGVzY3JpcHRpb24+IDwvcmRmOlJERj4gPC94OnhtcG1ldGE+IDw/eHBhY2tldCBlbmQ9InIiPz4+O9DRAAAABlBMVEX////MzMw46qqDAAAAKElEQVR42mJgxAUYcIJRLaNaRrWMahnqWkbDZVTLqJZRLSNPC0CAAQBc9ATjSuIEdwAAAABJRU5ErkJggg==')">
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
            function fileFieldOnChange(input) {
                var disable_buttons = false;

                var upload_form = $('#<?= $upload_form_id ?>');
                //var upload_form = $(input).closest('.upload_form');
                var storage_name = $(".upload_storage_name_input", upload_form).val();
                var file_name = $(".upload_image_file_input", upload_form).val();

                if ((storage_name == '') || (file_name == '')) {
                    disable_buttons = true;
                }

                if ((storage_name != '') && (file_name != '')) {
                    processUpload2(
                        '<?= \OLOG\Sanitize::sanitizeUrl($this->getActionUrl()) ?>',
                        storage_name,
                        '<?= Sanitize::sanitizeAttrValue(intval($this->getForceJpegImageFormat())) ?>');
                }
            }

            function processUpload2(upload_url, storage_name, force_jpeg_image_format) {
                var upload_form = $('#<?= $upload_form_id ?>');

                var file_path_in_storage_input_name = '<?= Sanitize::sanitizeAttrValue($this->getFilePathInStorageFieldName()) ?>';
                var storage_name_input_name = '<?= Sanitize::sanitizeAttrValue($this->getStorageFieldName()) ?>';

                var upload_image_file = $(".upload_image_file_input", upload_form)[0].files[0];
                if ((typeof upload_image_file == "undefined") || (upload_image_file == '')) {
                    return;
                }

                var form_data = new FormData();
                form_data.append("upload_storage_name", storage_name);
                form_data.append("<?= Sanitize::sanitizeAttrValue(\OLOG\ImageManager\ImageUploadAction::FORCE_JPEG_IMAGE_FORMAT_FIELD_NAME)?>", force_jpeg_image_format);
                form_data.append("upload_image_file", upload_image_file);

                var file_input = $(".upload_image_file_input", upload_form);
                file_input.attr("disabled", true);

                var upload_errors = $(".alert", upload_form);
                upload_errors.fadeOut();

                var progress_bar = $(".progress-bar", upload_form);
                var progress_bar_div = $(".progress", upload_form);
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
                    file_input.attr("disabled", false);
                    progress_bar_div.fadeOut();

                    if (!data.success) {
                        upload_errors.html(data.error_message);
                        upload_errors.fadeIn();
                        return;
                    }

                    $("input[name=" + file_path_in_storage_input_name + "]", upload_form).val(data.file_path_in_storage).trigger('change');
                    $("input[name=" + storage_name_input_name + "]", upload_form).val(data.storage_name).trigger('change');
                    $(".uploaded_image", upload_form).html('<img width="100%" src="' + data.image_url + '">');
                }).fail(function () {
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