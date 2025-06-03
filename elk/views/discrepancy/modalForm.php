<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\select2\Select2;
use kartik\date\DatePicker;
use kartik\datecontrol\DateControl;
use frontend\modules\elk\models\Discrepancy;

/* @var $this yii\web\View */
/* @var $model frontend\modules\elk\models\Discrepancy */
/* @var $form yii\widgets\ActiveForm */


?>


<h6><?='Несоответствие: ' . $id_reestr1[$id] . '<br>  Код объекта ЛК: '.$model->getKodInd($id)  ?></h6>
<div class="discrepency-form">

    <?php $form = ActiveForm::begin([
        'id' => 'modal-discrepency-form',
        'enableAjaxValidation' => true,
    ]); ?>


        <div class="col-md-12">
            <?= $form->field($model, 'discrepancy')->textInput(['maxlength' => true, ]) ?>
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
        <?= Html::a('Выйти', ['discrepancy/index', 'id' => $model->id_reestr], ['class' => 'btn coca_cola_gray']) ?>
    </div>


    <?php ActiveForm::end(); ?>
</div>

