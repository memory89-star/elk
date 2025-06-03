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
 * Автоматически заполняем поля № и Дату регестрации
 */

// /elk/reestr/search-field -- ссылка для промышленного сервера
// /frontend/web/elk/reestr/search-field -- ссылка для локальной работы

$date_id = "$('#id_department_kontrolled').change(function(){
    let date_id = $('#id_department_kontrolled').val();
    $.ajax({
//        url: 'search-field',
        url: '/frontend/web/elk/reestr/search-field',
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
 * Автоматически заполняем поля Год и Месяц
 */
$date_id = "$('#date_plan').change(function(){
    let date_id = $('#date_plan').val();
//    alert('!!!');
    $.ajax({
//        url: 'search-date',
        url: '/frontend/web/elk/reestr/search-date',
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
 * Автоматически заполняем поле Описание объекта ЛК
 */
$ob_id = "$('#id_objects').change(function(){
    let ob_id = $('#id_objects').val();
    $.ajax({
//        url: 'search-ob',
        url: '/frontend/web/elk/reestr/search-ob',
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


$st_id = "$('#id_step').change(function(){
    let st_id = $('#id_step').val();
    $.ajax({
//        url: 'define-step-status',
        url: '/frontend/web/elk/reestr/define-step-status',
        method: 'POST',
        data: {
            'st_id': st_id
        },
        success: function(data){
            $('#status').val(data['status']);
        }
    });
});";
$this->registerJs($st_id);


$Js = <<<SCRIPT

function getStepStatus(date_plan, date_fact, id_step)
{
    if (date_plan){
        $.ajax({
//             url: 'define-step-status',
             url: '/frontend/web/elk/reestr/define-step-status',
            method: 'POST',
            data: {'date_plan': date_plan,'date_fact':date_fact},
            success: function(data){
                let field = $(document.getElementById(id_step));
                field.val(data['status_desc']['desc']);
                field.css('color',data['status_desc']['color']);
                    $(document.getElementById('date_plan')).change(function(){
        getStepStatus($(document.getElementById('date_plan')).val(),$(document.getElementById('date_fact')).val(),'status');

    });
        $(document.getElementById('date_fact')).change(function(){
        getStepStatus($(document.getElementById('date_plan')).val(),$(document.getElementById('date_fact')).val(),'status');
    })
            }
        })
    }
}



$(document).ready(function() {
let id_step = $(document.getElementById('id_step'));

    $('select#id_step').change(function(){
        id_step.val($('select#id_step-id option:selected').text());            
        });

    getStepStatus($(document.getElementById('date_plan')).val(),$(document.getElementById('date_fact')).val(),'status');

    $(document.getElementById('date_plan')).change(function(){
        getStepStatus($(document.getElementById('date_plan')).val(),$(document.getElementById('date_fact')).val(),'status');
    });
    
    $(document.getElementById('date_fact')).change(function(){
        getStepStatus($(document.getElementById('date_plan')).val(),$(document.getElementById('date_fact')).val(),'status');
    })

    
});

SCRIPT;
$this->registerJs($Js);



$role1 = Yii::$app->user->can('elk_admin');
$role2 = Yii::$app->user->can('elk_user_gxk');
?>


<div class="reestr-form">

    <?php $form = ActiveForm::begin([
        'id' => 'modal-reestr-form',
        'enableAjaxValidation' => true,
    ]); ?>

    <div class="row">
        <div class="col-md-3">
            <?=  $form->field($model, 'date_detection')->widget(DateControl::classname(), [
                'options' => ['placeholder' => 'Введите дату ...'],
                'disabled' => true,
                'type' => DateControl::FORMAT_DATE,
                'widgetOptions' => [
                    'pluginOptions' => [
                        'autoclose' => true,
                        'id' => 'date_detection',
                        ]
                    ]
                ]);  ?>
        </div>

        <div class="col-md-3">
            <?= $form->field($model, 'date_registr')->textInput(['maxlength' => true, 'readonly' => 'true','id' => 'date_registr']) ?>
        </div>

        <div class="col-md-3">
            <?= $form->field($model, 'identification_document_number')->textInput(['maxlength' => true, 'readonly' => 'true','id' => 'identification_document_number']) ?>
        </div>

        <!-- Выбор Этап реализации-->
        <div class="reestr-form--row__id_step col-md-3">
            <?= $form->field($model, 'id_step')->widget(Select2::className(), [
            'data' => $id_step,
            'maintainOrder' => true,
            'options' => [
            'placeholder' => 'Укажите этап реализации',
            'disabled' => true,
            ],
            'pluginOptions' => ['allowClear' => true],
            ]);
            ?>
        </div>
        <!-- /Выбор Этап реализации -->

    </div>

    <div class="row">
        <!-- Выбор контролируемого подразделения -->
        <div class="reestr-form--row__id_department_kontrolled col-md-6">
            <?= $form->field($model, 'id_department_kontrolled')->widget(Select2::className(), [
                'data' => $id_department_kontrolled,
                'maintainOrder' => true,
                'options' => [
                    'placeholder' => 'Укажите контролируемое подразделение',
                    'id' => 'id_department_kontrolled',   'disabled' => 'true'
                    ],
                'pluginOptions' => ['allowClear' => true],
                ]); ?>

        </div>
        <!-- /Выбор контролируемого подразделения -->

        <!-- Выбор контролирующего подразделения -->
        <div class="reestr-form--row__id_department_kontrolling col-md-6">
            <?=  $form->field($model, 'id_department_kontrolling')->widget(Select2::className(), [
                'data' => $id_department_kontrolling,
                'maintainOrder' => true,
                'options' => [
                    'placeholder' => 'Укажите контролирующее подразделение',  'disabled' => 'true'
                    ],
                'pluginOptions' => ['allowClear' => true],
                ]); ?>

        </div>
        <!-- /Выбор контролирующего подразделения -->
    </div>

    <!-- Несоответствие/наблюдение -->
    <div class="department-form--row__incongruity col-md-9">
        <?= $form->field($model, 'incongruity')->textInput(['maxlength' => true, 'readonly' => 'true']) ?>
    </div>
    <!-- /Несоответствие/наблюдение  -->

    <div class="row">
        <!-- Выбор Объекта ЛК -->
        <div class="reestr-form--row__id_objects col-md-3">
            <?= $form->field($model, 'id_objects')->widget(Select2::className(), [
                'data' => $id_objects,
                'maintainOrder' => true,
                'options' => [
                    'placeholder' => 'Укажите код объекта ЛК', 'disabled' => 'true',
                    'id' => 'id_objects',
                    ],
                'pluginOptions' => ['allowClear' => true],
                ]); ?>

        </div>
        <!-- Выбор Объекта ЛК -->

        <!-- Описание объекта ЛК -->
        <div class="col-md-6">
            <?= $form->field($model, 'opisan')->textInput(['maxlength' => true, 'readonly' => 'true','id' => 'opisan']) ?>
        </div>
        <!-- Описание объекта ЛК -->

        <!-- Выбор Значимости несоответствия -->
        <div class="reestr-form--row__id_significance col-md-6">
            <?=  $form->field($model, 'id_significance')->widget(Select2::className(), [
                'data' => $id_significance,
                'maintainOrder' => true,
                'options' => [
                    'placeholder' => 'Укажите значимость несоответствия',  'disabled' => 'true',
                    ],
                'pluginOptions' => ['allowClear' => true],
                ]); ?>
        </div>
        <!-- Выбор Значимости несоответствия -->
    </div>


    <!-- Не выполнены требования -->
    <div class="reestr-form--row__requirements_not_met col-md-9">
        <?=  $form->field($model, 'requirements_not_met')->textInput(['maxlength' => true,'readonly' => 'true']); ?>
    </div>
    <!-- Не выполнены требования  -->

    <!-- Мероприятия по устранению несоответствий -->
    <div class="department-form--row__events_elimination col-md-9">
        <?= $form->field($model, 'events_elimination')->textInput(['maxlength' => true]) ?>
    </div>
    <!-- Мероприятия по устранению несоответствий  -->

    <!-- Причина доработкий -->
    <div class="department-form--row__events_elimination col-md-9">
        <?= $form->field($model, 'reason_modification')->textInput(['maxlength' => true]) ?>
    </div>
    <!-- Причина доработки  -->

    <div class="row  reestr-date">
        <!-- Плановый срок -->
        <div class="col-md-3">
            <?= $form->field($model, 'date_plan')->widget(DateControl::classname(), [
            'options' => ['placeholder' => 'Введите дату ...', 'id' => 'date_plan'
            ],
            'type' => DateControl::FORMAT_DATE,
            'widgetOptions' => [
            'pluginOptions' => [
            'autoclose' => true,
            ]
            ]
            ]) ?>
        </div>
        <!-- Плановый срок -->

        <!--Год  -->
        <div class="reestr-form--row__year col-md-2">
            <?= $form->field($model, 'year')->textInput(['maxlength' => true, 'readonly' => 'true', 'id' => 'year']) ?>
        </div>
        <!-- /Год -->

        <!-- Месяц -->
        <div class="reestr-form--row__month col-md-1">
            <?= $form->field($model, 'month')->textInput(['maxlength' => true, 'readonly' => 'true', 'id' => 'month']) ?>
        </div>
        <!-- /Месяц -->

        <!-- Фактичекая дата -->
        <div class="col-md-3">
            <?= $form->field($model, 'date_fact')->widget(DateControl::classname(), [
            'options' => ['placeholder' => 'Введите дату ...', 'id' => 'date_fact'],
            'type' => DateControl::FORMAT_DATE,
            'widgetOptions' => [
                'pluginOptions' => [
                'autoclose' => true,
                ]
            ]
            ]); ?>
        </div>
        <!-- Фактичекая дата -->

        <!-- Статус -->
        <div class="col-md-2">
            <label><b>Статус выполнения</b></label>
            <output id="status" style=""></output>
        </div>
        <!-- Статус -->
    </div>


    <div class="row">
        <!-- Выбор Ответственного -->
        <div class="reestr-form--row__id_otvetst col-md-6">
            <?= $form->field($model, 'id_otvetst')->widget(Select2::className(), [
            'data' => $cards,
            'maintainOrder' => true,
            'options' => [
            'placeholder' => 'Укажите ответственного',],
            'pluginOptions' => ['allowClear' => true],
            ]);
            ?>
        </div>
        <!-- Выбор Ответственного -->

        <!-- Выбор Контролирующего -->
        <div class="reestr-form--row__id_kontrol col-md-6">
            <?= $form->field($model, 'id_kontrol')->widget(Select2::className(), [
            'data' => $cards,
            'maintainOrder' => true,
            'options' => [
            'placeholder' => 'Укажите контролирующего',],
            'pluginOptions' => ['allowClear' => true],
            ]);
            ?>
        </div>
        <!-- Выбор Контролирующего -->
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
        <?= Html::a('Выйти', ['reestr/index'], ['class' => 'btn coca_cola_gray']) ?>
    </div>


    <?php ActiveForm::end(); ?>
</div>

