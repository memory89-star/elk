<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\select2\Select2;
use kartik\date\DatePicker;
use kartik\datecontrol\DateControl;
use frontend\modules\elk\models\Events;

/* @var $this yii\web\View */
/* @var $model frontend\modules\elk\models\Events */
/* @var $form yii\widgets\ActiveForm */

// /elk/events/search-date -- ссылка для промышленного сервера
// /frontend/web/elk/events/search-date -- ссылка для локальной работы

/**
 * Автоматически заполняем поля Год и Месяц
 */
$date_id = "$('#date_plan').change(function(){
    let date_id = $('#date_plan').val();
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


$st_id = "$('#id_discrepancy').change(function(){
    let st_id = $('#id_discrepancy').val();
//     alert('!!!');
    $.ajax({
        url: '/frontend/web/elk/events/define-step-status',
        method: 'POST',
        data: {
            'st_id': st_id
        },
        success: function(data){
//        alert(data['date']);
            $('#status').val(data['status']);
        }
    });
});";
$this->registerJs($st_id);


$Js = <<<SCRIPT

function getStepStatus(date_plan, date_fact, id_discrepancy)
{
    if (date_plan){
//    alert('!!!')
        $.ajax({
             url: '/frontend/web/elk/events/define-step-status',
            method: 'POST',
            data: {'date_plan': date_plan,'date_fact':date_fact},
            success: function(data){
//            alert(data['date']);
                let field = $(document.getElementById(id_discrepancy));
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
let events = $(document.getElementById('id_discrepancy'));

    $('select#id_discrepancy').change(function(){
        id_discrepancy.val($('select#id_discrepancy-id option:selected').text());            
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


?>


<!--<h6>--><?//='Несоответствие: ' . $model->getReestrInd($id) . '<br>  Код объекта ЛК: '.$model->getKodInd($id). '<br>  Причина несоответствия: '.$id_discrepancy[$id]  ?><!--</h6>-->
<div class="events-form">

    <?php $form = ActiveForm::begin([
        'id' => 'modal-events-form',
        'enableAjaxValidation' => true,
    ]); ?>


        <div class="col-md-12">
            <label class="form-group__label control-label" for="events"><?= 'Мероприятие по устранению причины' ?></label>
            <?= $form->field($model, 'events')->textInput(['maxlength' => true, 'id' => 'events'])
                ->label(false);
            ?>
        </div>

    <div class="row">
        <!-- Плановый срок -->
        <div class="col-md-3">
           <?= $form->field($model, 'date_plan')->widget(DateControl::classname(), [
                'options' => ['placeholder' => 'Введите дату ...', 'id' => 'date_plan'],
                'type' => DateControl::FORMAT_DATE,
                'widgetOptions' => [
                    'pluginOptions' => [
                    'autoclose' => true,

                    ]
                ]
            ]) ?>

        </div>
        <!-- Плановый срок -->

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
            ]) ?>
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
        <div class="events-form--row__id_otvetst col-md-6">
            <?php
              echo $form->field($model, 'id_otvetst')->widget(Select2::className(), [
            'data' => $cards,
            'maintainOrder' => true,
            'options' => [
            'placeholder' => 'Укажите ответственного',
                             'id' => 'id_department_kontrolled',],
            'pluginOptions' => ['allowClear' => true],
            ]);
            ?>
        </div>
        <!-- Выбор Ответственного -->

        <!-- Выбор Контролирующего -->
        <div class="events-form--row__id_kontrol col-md-6">
            <?php
              echo $form->field($model, 'id_kontrol')->widget(Select2::className(), [
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
        <?= Html::a('Выйти', ['events/index', 'id' => $model->id_discrepancy], ['class' => 'btn coca_cola_gray']) ?>
    </div>


    <?php ActiveForm::end(); ?>
</div>

