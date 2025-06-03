<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\select2\Select2;
use kartik\date\DatePicker;
use kartik\datecontrol\DateControl;
use frontend\modules\elk\models\Step;

/* @var $this yii\web\View */
/* @var $model frontend\modules\elk\models\Step */
/* @var $form yii\widgets\ActiveForm */
/* @var $id_podr // подразделение */


?>



<div class="step-form">

    <?php $form = ActiveForm::begin([
        'id' => 'modal-step-form',
        'enableAjaxValidation' => true,
    ]); ?>

    <div class="row">
        <div class="col-md-1">
            <?= $form->field($model, 'index_namber')->textInput(['maxlength' => true, 'placeholder' => 'Ввод п/п']) ?>
        </div>

        <div class="col-md-9">
            <?= $form->field($model, 'name')->textInput(['placeholder' => 'Ввод текста']) ?>
        </div>

        <div class="col-md-2">
            <?php
                    echo $form->field($model, 'block')->widget(Select2::className(), [
            'data' => $block,
            'maintainOrder' => true,
            'options' => ['placeholder' => 'Укажите блокировку (да/нет)'],
            'pluginOptions' => ['allowClear' => true]
            ]);
            ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'v_for_creating_doc')->checkbox() ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'v_after_accept_event')->checkbox() ?>
        </div>
    </div>

    <div class="reestr-form--row row">
        <!-- Создано -->
        <div class="reestr-form--row__created col-md-6">
            <div class="form-group">
                <label class="form-group__label control-label" for="created_user"><?= 'Создано' ?></label>
                <div class="form-group__container">
                    <input class="form-group__input form-control" type="datetime" id="date_time_create" readonly value="<?= $date_time_create ?>">
                    <input class="from-group__input form-control" type="text" id="user_first" readonly value="<?= $user_last ?>">
                </div>
            </div>
        </div>
        <!-- /Создано -->

        <!-- Изменено -->
        <div class="reestr-form--row__updated col-md-6">
            <div class="form-group">
                <label class="form-group__label control-label" for="updated_user"><?= 'Изменено' ?></label>
                <div class="form-group__container">
                    <input class="form-group__input form-control" type="datetime" id="date_time_update" readonly value="<?= $date_time_update ?>">
                    <input class="form-group__input form-control" type="text" id="user_last" readonly value="<?= $user_first ?>">
                </div>
            </div>
        </div>
        <!-- /Изменено -->
    </div>

    <div class="modal-footer">
        <?= Html::submitButton('Сохранить', ['class' => 'btn coca_cola_green']) ?>
        <?= Html::a('Выйти', ['step/index'], ['class' => 'btn coca_cola_gray']) ?>
    </div>


    <?php ActiveForm::end(); ?>
</div>

