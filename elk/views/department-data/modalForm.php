<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model frontend\modules\elk\models\DepartmentData */
/* @var $form yii\widgets\ActiveForm */

/**
 * Автоматически заполняем поля по подразделению
 */
$department_id = "$('#emp_department_id').change(function(){
    let dep_id = $('#emp_department_id').val();
    $.ajax({
        url: 'search-field',
        method: 'POST',
        data: {
            'dep_id': dep_id
        },
        success: function(data){ 
        var code = data['emp_department_code'].slice(1,data['emp_department_code'].length);
            $('#emp_department_code').val(code);
            $('#emp_department_name').val(data['emp_department_name']);
            $('#emp_department_full_name').val(data['emp_department_full_name']);
        }
    });
});";
$this->registerJs($department_id);

?>

<div class="card">
    <div class="card-body">
        <div class="department-data-form">
            <?php $form = ActiveForm::begin([
                'id' => 'modal-department-data-form',
                'enableAjaxValidation' => true,
            ]); ?>

            <div class="department-data--row row">
                <!-- Выбор подразделения -->
                <div class="department-form--row__emp_department_id col-md-6">
                    <?php
                        echo $form->field($model, 'emp_department_id')->widget(Select2::className(), [
                    'data' => $emp_department_id,
                    'maintainOrder' => true,
                    'options' => [
                    'placeholder' => 'Укажите подразделение',
                    'id' => 'emp_department_id',
                    ],
                    'pluginOptions' => ['allowClear' => true],
                    ]);
                    ?>
                </div>
                <!-- /Выбор подразделения -->
            </div>

            <div class="department-data--row row">
                <!-- Код Подразделение -->
                <div class="department-form--row__emp_department_code col-md-3">
                    <?= $form->field($model, 'emp_department_code')->textInput(['maxlength' => true, 'id' => 'emp_department_code']) ?>
                </div>
                <!-- /Код Подразделение -->

                <!-- Краткое наименование подразделения -->
                <div class="department-form--row__emp_department_name col-md-3">
                    <?= $form->field($model, 'emp_department_name')->textInput(['maxlength' => true, 'id' => 'emp_department_name']) ?>
                </div>
                <!-- /Краткое наименование подразделения -->

                <!-- Блокировка -->
                <div class="department-form--row__block col-md-3">
                    <?php
                        echo $form->field($model, 'block')->widget(Select2::className(), [
                    'data' => $block,
                    'maintainOrder' => true,
                    'options' => ['placeholder' => 'Укажите блокировку (да/нет)'],
                    'pluginOptions' => ['allowClear' => true]
                    ]);
                    ?>
                </div>
                <!-- /Блокировка -->
            </div>

            <div class="department-data--row row">
                <!-- Полное наименование подразделения -->
                <div class="department-form--row__emp_department_full_name col-md-12">
                    <?= $form->field($model, 'emp_department_full_name')->textarea(['maxlength' => true, 'id' => 'emp_department_full_name']) ?>
                </div>
                <!-- /Полное наименование подразделения -->
            </div>

            <div class="department-data--row row">
                <!-- /Тип подразделения -->
                <div class="col-md-6">
                    <?php
                echo $form->field($model, 'emp_department_type')->widget(Select2::className(), [
                    'data' => $emp_department_type,
                    'maintainOrder' => true,
                    'options' => [
                    'placeholder' => 'Укажите тип...',
                    ],
                    'pluginOptions' => [
                    'allowClear' => true,
                    ],
                    ]);
                    ?>
                </div>
                <!-- /Тип подразделения -->

                <!-- Номер документа -->
                <div class="department-form--row__doc_num_max col-md-6">
                    <?= $form->field($model, 'doc_num_max')->textInput(['maxlength' => true]) ?>
                </div>
                <!-- /Номер документа -->

                <!-- Ответственный -->
                <div class="action-form--row__manager col-md-4">
                    <?php
                        echo $form->field($model, 'manager')->widget(Select2::className(), [
                    'data' => $cards,
                    'maintainOrder' => true,
                    'options' => [
                    'placeholder' => 'Укажите ответственного...',
                    'id' => 'manager',
                    ],
                    'pluginOptions' => ['allowClear' => true],
                    ]);
                    ?>
                </div>
                <!-- /Ответственный -->


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





            <!-- Кнопки -->
            <div class="modal-footer">
                <?= Html::submitButton('Сохранить', ['class' => 'btn coca_cola_green']) ?>
                <?= Html::a('Выйти', ['department-data/index'], ['class' => 'btn coca_cola_gray']) ?>
            </div>
            <!-- /Кнопки -->

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>


