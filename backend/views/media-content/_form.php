<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model common\models\MediaContent */
/* @var $form yii\widgets\ActiveForm */
/* @var $is_update boolean */
?>

<div class="media-content-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <!-- Left column -->
        <div class="col-lg-6">
            <div class="box">
                <div class="box-body">
                    <?= $form->field($model, 'name')->textInput(['maxlength' => 255]) ?>

                    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

                    <?= $form->field($model, 'tagList')->widget(Select2::className(), [
                        'theme' => Select2::THEME_KRAJEE_BS4,
                        'value' => ($model->tags ? explode(' ', $model->tags) : null),
                        'options' => ['placeholder' => Yii::t('app', 'Add tags ...'), 'multiple' => true],
                        'pluginOptions' => [
                            'tags' => true,
                            'tokenSeparators' => [',', ' '],
                            'allowClear' => true
                        ],
                    ]) ?>

                    <div class="form-group">
                        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
                    </div>
                </div>
            </div>
        </div>
        <!-- Right column -->
        <div class="col-lg-6">
            <?php if (!$is_update): ?>
                <div class="box">
                    <div class="box-body">
                        <?= $form->field($model, 'uploadFiles')->widget(FileInput::classname(), [
                            'options' => ['accept' => 'video/*', 'multiple' => true],
                            'pluginOptions' => [
                                'previewFileType' => 'any',
                                //'uploadUrl' => Url::to(['/manage-licenses/create-file-upload']),
                                'maxFileCount' => 10,
                                'showPreview' => true,
                                'showCaption' => true,
                                'showRemove' => true,
                                'showUpload' => false,
                                //'allowedFileExtensions' => ['avi', 'mp3', 'mp4', 'mkv', 'flv', 'gif', 'wmv', 'mpg'],
                                'initialPreviewAsData' => true,
                                'initialPreviewFileType' => 'video',
                                'initialPreviewConfig' => [
                                    ['filetype' => "video/mp4"]
                                ],
                            ],
                        ]); ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>

    </div>

    <?php ActiveForm::end(); ?>

</div>
