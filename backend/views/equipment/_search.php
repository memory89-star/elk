<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\modules\equipment\models\EquipmentSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="equipment-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'description') ?>

    <?= $form->field($model, 'equipment_type_id') ?>

    <?= $form->field($model, 'equipment_type') ?>

    <?php // echo $form->field($model, 'release_date') ?>

    <?php // echo $form->field($model, 'factory_number') ?>

    <?php // echo $form->field($model, 'passport_num') ?>

    <?php // echo $form->field($model, 'release_year') ?>

    <?php // echo $form->field($model, 'baan_status_id') ?>

    <?php // echo $form->field($model, 'baan_status_name') ?>

    <?php // echo $form->field($model, 'responsible_card_id') ?>

    <?php // echo $form->field($model, 'responsible_stabnum') ?>

    <?php // echo $form->field($model, 'responsible_name') ?>

    <?php // echo $form->field($model, 'department_id') ?>

    <?php // echo $form->field($model, 'dep_code') ?>

    <?php // echo $form->field($model, 'dep_name') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
