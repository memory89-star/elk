<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use kartik\file\FileInput;
use yii\helpers\Url;

$this->title = 'Файлы приложений';

$this->registerJs("
    $(document).ready(function () {
        $('.fileinput-upload-button').hide()
    });
    function sleep(ms) {
        return new Promise(resolve => setTimeout(resolve, ms));
    };
");

$errorMessage = 'function(event, data, msg) {
    $("div.file-error-message").remove();

    sleep(800).then(() => {
        var str = \'<div class="alert-danger alert alert-dismissible" role="alert">\';
        str += \'<i class="icon fas fa-ban"></i>\';
        str += \'<small>\' + data["jqXHR"]["responseText"] + \'</small>\';
        str += \'<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span></button>\';
        str += \'</div>\';

        $("#mesActionController").append(str);
    });

    sleep(10000).then(() => {
        $("#mesActionController").empty();
    });
}';

$successMessage = 'function(event, data, msg) {
    $("div.file-error-message").remove();

    sleep(100).then(() => {
        var str = \'<div class="alert-success alert alert-dismissible" role="alert">\';
        str += \'<i class="icon fas fa-check"></i>\';
        //успешное сохранение
        if (data["response"] != undefined) {
            str += \'<small>\' + data["response"]["success"] + \'</small>\';

            $("button.kv-file-remove").hide();
            if ($("a.kv-file-download").parent().find("button.kv-file-remove").attr("title") == "Удалить файл") $("a.kv-file-download").parent().find("button.kv-file-remove").show();
            sleep(10000).then(() => { $("#modalContent").load("'.Url::to(["lkz-list-file-upload/index"]).'&pathSubDir='.$pathSubDir.'"); });
        }
        //успешное удаление
        if (msg["responseJSON"] != undefined) str += \'<small>\' + msg["responseJSON"]["success"] + \'</small>\';
        str += \'<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span></button>\';
        str += \'</div>\';

        $("#mesActionController").append(str);
    });

    sleep(10000).then(() => {
        $("#mesActionController").empty();
    });
}';

//$lkz_admin = Yii::$app->user->can('lkz_admin');
?>

<div class="file-index">
    <?php
            echo '<div class="file-index__container">';
            echo '<h5>'?><?= Html::encode("Документы: ". $pathSubDir) ?><?php echo '</h5>';
            echo '<h6>'?><?= Html::encode("Прикрепить файлы. Допустимые форматы: ".implode(', ', $arrExt)) ?><?php echo '</h6>';
            echo '<div id="mesActionController" class="file-index__container--message">';
            echo '</div>';

            if (!is_null($previewImg)) {
                $form = ActiveForm::begin(['options' => [
                    'enctype' => 'multipart/form-data',
                    'action' => 'upload',
                    'enableAjaxValidation' => true,
                ]]);

                if (Yii::$app->user->can("elk_admin")) {
                    echo $form->field($modelFile, 'fileName')->widget(FileInput::className(), [
                    'options' => [
                        'id' => 'fileName',
                        'multiple' => true,
                        'maxFileCount' => 10,
                    ],
                    'pluginEvents' => [
                        'fileuploaderror' => new \yii\web\JsExpression($errorMessage),
                        'filedeleteerror' => new \yii\web\JsExpression($errorMessage),
                        'fileuploaded' => new \yii\web\JsExpression($successMessage),
                        'filedeleted' => new \yii\web\JsExpression($successMessage)
                    ],
                    'pluginOptions' => [
                        'layoutTemplates' => [
                            'actions' => '
                                <div class="file-actions">
                                    <div class="file-footer-buttons">
                                         {download} {delete}
                                    </div>
                                </div>
                            ',
                        ],
                        'initialPreview' => isset($previewImg[1]) ? $previewImg[1] : "",
                        'initialPreviewConfig' => isset($previewImg[0]) ? $previewImg[0] : "",
                        'initialPreviewDownloadUrl' => Url::to(['file/download']),
                        'uploadUrl' => Url::to(['file/upload', 'pathSubDir' => $pathSubDir]),
                        'deleteUrl' => Url::to(['file/delete', 'pathSubDir' => $pathSubDir]),
                        'allowedFileExtensions' => $arrExt,
                        'showPreview' => true,
                        'showUpload' => true,
                        'showBrowse' => true,
                        'showCaption' => false,
                        'showRemove' => false,
                        'overwriteInitial' => false,
                        'dropZoneEnabled' => false,
                        'showClose' => false,
                        'showCancel' => false,
                        'maxFileSize' => 2097152,
                    ],
                    ]);
                } else {
                    echo $form->field($modelFile, 'fileName')->widget(FileInput::className(), [
                            'options' => [
                                'id' => 'fileName',
                                'multiple' => true,
                                'maxFileCount' => 10,
                            ],
                            'pluginEvents' => [
                                'fileuploaderror' => new \yii\web\JsExpression($errorMessage),
                                'filedeleteerror' => new \yii\web\JsExpression($errorMessage),
                                'fileuploaded' => new \yii\web\JsExpression($successMessage),
                                'filedeleted' => new \yii\web\JsExpression($successMessage)
                            ],
                            'pluginOptions' => [
                                'layoutTemplates' => [
                                    'actions' => '
                                        <div class="file-actions">
                                            <div class="file-footer-buttons">
                                                {download}
                                            </div>
                                        </div> ',
                                ],
                                'initialPreview' => isset($previewImg[1]) ? $previewImg[1] : "",
                                'initialPreviewConfig' => isset($previewImg[0]) ? $previewImg[0] : "",
                                'initialPreviewDownloadUrl' => Url::to(['file/download']),
                                'uploadUrl' => Url::to(['file/upload', 'pathSubDir' => $pathSubDir]),
                                'deleteUrl' => Url::to(['file/delete', 'pathSubDir' => $pathSubDir]),
                                'allowedFileExtensions' => $arrExt,
                                'showPreview' => true,
                                'showUpload' => true,
                                'showBrowse' => false,
                                'showCaption' => false,
                                'showRemove' => false,
                                'overwriteInitial' => false,
                                'dropZoneEnabled' => false,
                                'showClose' => false,
                                'showCancel' => false,
                                'maxFileSize' => 2097152,
                            ],
                        ]);
                }

                ActiveForm::end();

            } else {
                echo '<div class="file-index__container alert-danger alert alert-dismissible" role="alert">';
                    echo '<i class="file-index__container--icon icon fas fa-ban"></i>';
                    echo '<small>Проверьте каталог хранения файлов в параметре FileUpload в frontend/config/params-local.php ... </small>';
                echo '</div>';
           }

            echo '<div class="button-group">';
                echo Html::a('Сохранить', '#', ['style' => 'margin-right: 5px','class' => 'btn btn-shadow btn-success', 'onclick' => "$('.fileinput-upload-button').click()"]);
                echo Html::a('Закрыть', ['reestr/index'], ['class' => 'btn btn-shadow btn-secondary']);
            echo '</div>';

        echo '</div>';
    ?>
</div>
