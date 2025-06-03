<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\select2\Select2;
use kartik\datecontrol\DateControl;

/* @var $this yii\web\View */
/* @var $model frontend\modules\elk\models\ReestrPrint */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Печать реестра ЭЛК';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="card">
    <div class="card-body">
        <div class="reestr-print-form">
            <?php $form = ActiveForm::begin([
                'id' => 'risk-reestr-form',
            ]); ?>

            <div class="reestr-print--row row">
                <!-- контролируемое подразделение "С" -->
                <div class="reestr-print--row__departnemt_kon_begin col-md-4">
                    <?php
                        echo $form->field($model, 'departnemt_kon_begin')->widget(Select2::className(), [
                    'data' => $model->getDepartment(),
                    'maintainOrder' => true,
                    'options' => [
                    'placeholder' => 'Укажите контролируемое подразделение с...',
                    'id' => 'departnemt_kon_begin',
                    ],
                    'pluginOptions' => ['allowClear' => true],
                    ]);
                    ?>
                </div>
                <!-- /контролируемое подразделение "С" -->

                <!-- контролируемое подразделение "ПО" -->
                <div class="reestr-print--row__departnemt_kon_end col-md-4">
                    <?php
                        echo $form->field($model, 'departnemt_kon_end')->widget(Select2::className(), [
                    'data' => $model->getDepartment(),
                    'maintainOrder' => true,
                    'options' => [
                    'placeholder' => 'Укажите контролируемое подразделение по...',
                    'id' => 'departnemt_kon_end',
                    ],
                    'pluginOptions' => ['allowClear' => true],
                    ]);
                    ?>
                </div>
                <!-- /контролируемое подразделение "ПО" -->
            </div>

            <div class="reestr-print--row row">
                <!-- Контролирующее подразделение "С" -->
                <div class="reestr-print--row__department_begin col-md-4">
                    <?php 
                        echo $form->field($model, 'departnemt_begin')->widget(Select2::className(), [
                            'data' => $model->getDepartmentl(),
                            'maintainOrder' => true,
                            'options' => [
                                'placeholder' => 'Укажите контролирующее подразделение с...',
                                'id' => 'departnemt_begin',
                            ],
                            'pluginOptions' => ['allowClear' => true],
                        ]);
                    ?>
                </div>
                <!-- /Контролирующее подразделение "С" -->

                <!-- Контролирующее подразделение "ПО" -->
                <div class="reestr-print--row__departnemt_end col-md-4">
                    <?php 
                        echo $form->field($model, 'departnemt_end')->widget(Select2::className(), [
                            'data' => $model->getDepartmentl(),
                            'maintainOrder' => true,
                            'options' => [
                                'placeholder' => 'Укажите контролирующее подразделение по...',
                                'id' => 'departnemt_end',
                            ],
                            'pluginOptions' => ['allowClear' => true],
                        ]);
                    ?>
                </div>
                <!-- /Контролирующее подразделение "ПО" -->
            </div>
            <div class="reestr-print--row row">
                <!-- Дата выявления "C" -->
                <div class="reestr-print--row__date_begin col-md-4">
                    <?php 
                        echo $form->field($model, 'date_begin')->widget(DateControl::className(), [
                            'model' => $model,
                            'attribute' => 'date_begin',
                            'type' => DateControl::FORMAT_DATE,
                            'ajaxConversion' => false,
                            'widgetOptions' => [
                                'pluginOptions' => [
                                    'autoclose' => true,
                                    'todayHighlight' => true,
                                ],
                                'options' => [
                                    'disabled' => false,
                                ],
                            ],
                        ]);
                    ?>
                </div>
                <!-- /Дата выявления "C" -->

                <!-- Дата выявления "ПО" -->
                <div class="reestr-print--row__date_end col-md-4">
                    <?php 
                        echo $form->field($model, 'date_end')->widget(DateControl::className(), [
                            'model' => $model,
                            'attribute' => 'date_begin',
                            'type' => DateControl::FORMAT_DATE,
                            'ajaxConversion' => false,
                            'widgetOptions' => [
                                'pluginOptions' => [
                                    'autoclose' => true,
                                    'todayHighlight' => true,
                                ],
                                'options' => [
                                    'disabled' => false,
                                ],
                            ],
                        ]);
                    ?>
                </div>
                <!-- /Дата выявления "ПО" -->
            </div>

            <!-- Кнопки -->
            <div class="button">
                <?= Html::submitButton('Печать', ['class' => 'btn btn-success']) ?>
            </div>
            <!-- /Кнопки -->

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
