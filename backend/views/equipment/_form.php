<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\modules\equipment\models\Equipment */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="equipment-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'equipment_type_id')->textInput() ?>

    <?= $form->field($model, 'equipment_type')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'release_date')->textInput() ?>

    <?= $form->field($model, 'factory_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'passport_num')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'release_year')->textInput() ?>

    <?= $form->field($model, 'baan_status_id')->textInput() ?>

    <?= $form->field($model, 'baan_status_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'responsible_card_id')->textInput() ?>

    <?= $form->field($model, 'responsible_stabnum')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'responsible_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'department_id')->textInput() ?>

    <?= $form->field($model, 'dep_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'dep_name')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
