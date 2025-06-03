<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\select2\Select2;
use kartik\date\DatePicker;
use kartik\datecontrol\DateControl;
use frontend\modules\elk\models\Reestr;

/* @var $this yii\web\View */
/* @var $model frontend\modules\elk\models\Reestr */
/* @var $form yii\widgets\ActiveForm */


/**
 * Автоматически заполняем поля Год и Месяц
 */
$date_id = "$('#date_detection').change(function(){
    let date_id = $('#date_detection').val();
//    alert('!!!');
    $.ajax({
        url: 'search-date',
        method: 'POST',
        data: {
            'date_id': date_id
        },
        success: function(data){ 
//        alert(data['date']);
            $('#year').val(data['year']);
            $('#month').val(data['month']);
        }
    });
});";
$this->registerJs($date_id);

/**
 * Автоматически заполняем поля № и Дату регестрации
 */
$date_id = "$('#id_department_kontrolled').change(function(){
    let date_id = $('#id_department_kontrolled').val();
    $.ajax({
        url: 'search-field',
        method: 'POST',
        data: {
            'date_id': date_id
        },
        success: function(data){ 
            $('#identification_document_number').val(data['identification_document_number']);
        }
    });
});";
$this->registerJs($date_id);

/**
 * Автоматически заполняем поле Описание объекта ЛК
 */
$ob_id = "$('#id_objects').change(function(){
    let ob_id = $('#id_objects').val();
    $.ajax({
        url: 'search-ob',
        method: 'POST',
        data: {
            'ob_id': ob_id
        },
        success: function(data){ 
            $('#opisan').val(data['opisan']);
        }
    });
});";
$this->registerJs($ob_id);

?>



<div class="reestr-form">

    <?php $form = ActiveForm::begin([
        'id' => 'modal-reestr-form',
        'enableAjaxValidation' => true,
    ]); ?>

    <div class="row">
        <div class="col-md-3">
            <?= $form->field($model, 'date_detection')->widget(DateControl::classname(), [
                'options' => ['placeholder' => 'Введите дату ...',],
                'type' => DateControl::FORMAT_DATE,
                'widgetOptions' => [
                    'pluginOptions' => [
                    'autoclose' => true,
                    'id' => 'date_detection',
                    ]
                ]
            ]) ?>
        </div>

        <div class="col-md-3">
            <?= $form->field($model, 'date_registr')->textInput(['maxlength' => true, 'readonly' => 'true','id' => 'date_registr']) ?>
        </div>

        <div class="col-md-2">
            <?= $form->field($model, 'identification_document_number')->textInput(['maxlength' => true, 'readonly' => 'true','id' => 'identification_document_number']) ?>
        </div>

<!--        <div class="col-md-2">-->
<!--            --><?//= $form->field($model, 'id_step')->textInput(['maxlength' => true, 'readonly' => 'true','id' => 'id_step']) ?>
<!--        </div>-->

        <!-- Этап реализации id -->
            <div class="reestr-form--row__id_step col-md-4">
                <?php
                        echo $form->field($model, 'id_step')->widget(Select2::className(), [
                'data' => $step,
                'maintainOrder' => true,
                'options' => [
                'placeholder' => 'Укажите этап реализации...',
                'id' => 'id_step',   'disabled' => 'true'
                ],
                'pluginOptions' => ['allowClear' => true],
                ]);
                ?>
            </div>
        <!-- /Этап реализации id -->

    </div>

    <div class="row">
        <!-- Выбор контролируемого подразделения -->
        <div class="reestr-form--row__id_department_kontrolled col-md-6">
            <?php
              echo $form->field($model, 'id_department_kontrolled')->widget(Select2::className(), [
                'data' => $id_department_kontrolled,
                'maintainOrder' => true,
                'options' => [
                    'placeholder' => 'Укажите контролируемое подразделение',
                    'id' => 'id_department_kontrolled',
                    ],
                 'pluginOptions' => ['allowClear' => true],
              ]);
            ?>
        </div>
        <!-- /Выбор контролируемого подразделения -->

        <!-- Выбор контролирующего подразделения -->
        <div class="reestr-form--row__id_department_kontrolling col-md-6">
            <?php
              echo $form->field($model, 'id_department_kontrolling')->widget(Select2::className(), [
            'data' => $id_department_kontrolling,
            'maintainOrder' => true,
            'options' => [
                'placeholder' => 'Укажите контролируемое подразделение',
                ],
            'pluginOptions' => ['allowClear' => true],
            ]);
            ?>
        </div>
        <!-- /Выбор контролирующего подразделения -->
    </div>

    <!-- Несоответствие/наблюдение -->
    <div class="department-form--row__incongruity col-md-9">
        <?= $form->field($model, 'incongruity')->textInput(['maxlength' => true]) ?>
    </div>
    <!-- /Несоответствие/наблюдение  -->

    <!-- Сопровождающий -->
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
    <!-- /Сопровождающий -->

    <div class="row">
        <!-- Выбор Объекта ЛК -->
        <div class="reestr-form--row__id_objects col-md-3">
            <?php
               echo $form->field($model, 'id_objects')->widget(Select2::className(), [
                'data' => $id_objects,
                'maintainOrder' => true,
                'options' => [
                    'placeholder' => 'Укажите код объекта ЛК',
                    'id' => 'id_objects',
                    ],
                'pluginOptions' => ['allowClear' => true],
               ]);
            ?>
        </div>
        <!-- Выбор Объекта ЛК -->

        <!-- Описание объекта ЛК -->
        <div class="col-md-6">
            <?= $form->field($model, 'opisan')->textInput(['maxlength' => true, 'readonly' => 'true','id' => 'opisan']) ?>
        </div>
        <!-- Описание объекта ЛК -->

        <!-- Выбор Значимости несоответствия -->
        <div class="reestr-form--row__id_significance col-md-6">
            <?php
               echo $form->field($model, 'id_significance')->widget(Select2::className(), [
            'data' => $id_significance,
            'maintainOrder' => true,
            'options' => [
            'placeholder' => 'Укажите значимость несоответствия',
            ],
            'pluginOptions' => ['allowClear' => true],
            ]);
            ?>
        </div>
        <!-- Выбор Значимости несоответствия -->
    </div>

    <!-- Не выполнены требования -->
    <div class="department-form--row__requirements_not_met col-md-9">
        <?= $form->field($model, 'requirements_not_met')->textInput(['maxlength' => true]) ?>
    </div>
    <!-- Не выполнены требования  -->

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
        <?= Html::a('Выйти', ['reestr/index'], ['class' => 'btn coca_cola_gray']) ?>
    </div>


    <?php ActiveForm::end(); ?>
</div>

