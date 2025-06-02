<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model backend\models\SchedulerJobs */
/* @var $form yii\widgets\ActiveForm */
/* @var $users \common\modules\userProfile\models\UserProfile */
/* @var $frequency_types array */
/* @var $status array */

?>

<div class="scheduler-jobs-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="box">
        <div class="box-body">

            <?= $form->field($model, 'job_action')->textInput(['maxlength' => true])->label(Yii::t('app', 'Job Action')) ?>

            <?= $form->field($model, 'module')->textInput(['maxlength' => true])->label(Yii::t('app', 'Module')) ?>

            <?= $form->field($model, 'status')->widget(Select2::class, [
                'theme' => Select2::THEME_KRAJEE_BS4,
                'model' => $model,
                'name' => 'SchedulerJobs[status]',
                'value' => $model->status,
                'data' => $status,
                'options' => [
                    'placeholder' => Yii::t('app', 'Select a status ...'),
                ],
                'pluginOptions' => [
                    'allowClear' => false
                ],
            ]); ?>

            <?= $form->field($model, 'frequency_type')->widget(Select2::class, [
                'theme' => Select2::THEME_KRAJEE_BS4,
                'model' => $model,
                'name' => 'SchedulerJobs[frequency_type]',
                'value' => $model->frequency_type,
                'data' => $frequency_types,
                'options' => [
                    'placeholder' => Yii::t('app', 'Select a frequency type ...'),
                ],
                'pluginOptions' => [
                    'allowClear' => false
                ],
            ]); ?>

            <?= $form->field($model, 'frequency')->textInput(['maxlength' => true])->label(Yii::t('app', 'Frequency')) ?>

            <?= $form->field($model, 'notify_user_id')->widget(Select2::class, [
                'theme' => Select2::THEME_KRAJEE_BS4,
                'model' => $model,
                'name' => 'SchedulerJobs[notify_user_id]',
                'value' => $model->notify_user_id,
                'data' => $users,
                'options' => [
                    'placeholder' => Yii::t('app', 'Select a user ...'),
                ],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>

            <?= $form->field($model, 'is_notify')->checkbox() ?>

            <?= $form->field($model, 'priority')->textInput(['maxlength' => true])->label(Yii::t('app', 'Priority')) ?>

            <?= $form->field($model, 'description')->textarea(['maxlength' => true])->label(Yii::t('app', 'Description')) ?>

            <div class="form-group">
                <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
             </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
