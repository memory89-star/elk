<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $searchModel frontend\modules\employee\models\search\DepartmentSearch */
/* @var $form yii\widgets\ActiveForm */
/* @var $mainDepartments array */
/* @var $authItems array */

$class = ''; //Yii::$app->user->can('hd_admin') ? '' : ' d-none';
?>

<div class="department-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <div class="row row-eq-height align-items-end">
        <div class="col-4">
            <?= $form->field($searchModel, 'main_department_id')->widget(Select2::className(), [
                'data' => $mainDepartments,
                'options' => [
                    'id' => 'department-id-search',
                    'placeholder' => 'Выберите из списка'
                ],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>
        </div>
        <div class="col-3<?= $class ?>">
            <?= $form->field($searchModel, 'auth_item_name')->widget(Select2::className(), [
                'data' => $authItems,
                'options' => [
                    'id' => 'auth-item-name-search',
                    'placeholder' => 'Выберите из списка'
                ],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>
        </div>
        <div class="col-2">
            <div class="form-group">
                <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
