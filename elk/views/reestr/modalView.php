<?php

use yii\helpers\Html;
use yii\bootstrap4\Tabs;
use kartik\date\DatePicker;
use kartik\form\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model frontend\modules\elk\models\Reestr */
/* @var $form yii\widgets\ActiveForm */

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
<div class="card">
            <?php $form = ActiveForm::begin([
                'id' => 'modal-reestr-view-form',
                'enableAjaxValidation' => true,
                'options' => ['enctype' => 'multipart/form-data'],
            ]); ?>

    <div class="row">
            <!-- Дата выявления -->
            <div class="reestr-form--row__units col-md-3">
                <label class="control-label" for="units"><?= 'Дата выявления' ?></label>
                <input type="text" id="units" class="form-control" readonly value="<?= $model->date_detection ?>">
            </div>
            <!-- /Дата выявления -->

            <!-- Дата регистрации -->
            <div class="reestr-form--row__units col-md-3">
                <label class="control-label" for="units"><?= 'Дата регистрации' ?></label>
                <input type="text" id="units" class="form-control" readonly value="<?= $model->date_registr ?>">
            </div>
            <!-- /Дата регистрации -->

        <!-- Рег. № -->
        <div class="reestr-form--row__units col-md-3">
            <label class="control-label" for="units"><?= 'Рег. №' ?></label>
            <input type="text" id="units" class="form-control" readonly value="<?= $model->identification_document_number ?>">
        </div>
        <!-- /Рег. № -->

        <!-- Этап реализации -->
        <div class="reestr-form--row__units col-md-3">
            <label class="control-label" for="units"><?= 'Этап реализации' ?></label>
            <input type="text" id="units" class="form-control" readonly value="<?= ($model->id_step != NULL)?$model->getNameStep($model->id_step):NULL ?>">
        </div>
        <!-- /Этап реализации -->
    </div>

    <div class="row">

        <!-- Контролируемое подразделение -->
        <div class="reestr-form--row__units col-md-3">
            <label class="control-label" for="units"><?= 'Контролируемое подразделение' ?></label>
            <input type="text" id="units" class="form-control" readonly value="<?= $model->getDepartmentKontr($model->id_department_kontrolling) ?>">
        </div>
        <!-- /Контролируемое подразделение -->

        <!-- Контролирующее подразделение -->
        <div class="reestr-form--row__units col-md-3">
            <label class="control-label" for="units"><?= 'Контролирующее подразделение' ?></label>
            <input type="text" id="units" class="form-control" readonly value="<?= $model->getDepartmentKontr($model->id_department_kontrolled) ?>">
        </div>
        <!-- /Контролирующее подразделение -->

    </div>

    <div class="row">

        <!-- Сопровождающий -->
        <div class="reestr-form--row__units col-md-6">
            <label class="control-label" for="manager"><?= $model->getAttributeLabel('manager') ?></label>
            <input type="text" id="manager" class="form-control" readonly value="<?= \frontend\modules\elk\models\Card::getFioByCardId($model->manager) ?>">
        </div>
        <!-- /Сопровождающий -->

        <!-- Несоответствие/наблюдение -->
        <div class="reestr-form--row__units col-md-12">
            <label class="control-label" for="units"><?= 'Рег. №' ?></label>
            <input type="text" id="units" class="form-control" readonly value="<?= $model->incongruity ?>">
        </div>
        <!-- /Несоответствие/наблюдение -->

    </div>

    <div class="row">

        <!-- Выбор Объекта ЛК  -->
        <div class="reestr-form--row__units col-md-3">
            <label class="control-label" for="units"><?= 'Код объекта ЛК ' ?></label>
            <input type="text" id="units" class="form-control" readonly value="<?= $model->getKod($model->id_objects) ?>">
        </div>
        <!-- /Выбор Объекта ЛК  -->

        <!-- Описание объекта ЛК -->
        <div class="reestr-form--row__units col-md-6">
            <label class="control-label" for="units"><?= 'Описание объекта ЛК' ?></label>
            <input type="text" id="units" class="form-control" readonly value="<?= $model->opisan ?>">
        </div>
        <!-- /Описание объекта ЛК -->

        <!-- Выбор Значимости несоответствия -->
        <div class="reestr-form--row__units col-md-3">
            <label class="control-label" for="units"><?= 'Значимость несоответствия' ?></label>
            <input type="text" id="units" class="form-control" readonly value="<?= $model->getZn($model->id_significance) ?>">
        </div>
        <!-- /Выбор Значимости несоответствия

    </div>

     <div class="row">

            <!-- Не выполнены требования -->
        <div class="reestr-form--row__units col-md-12">
            <label class="control-label" for="units"><?= 'Не выполнены требования' ?></label>
            <input type="text" id="units" class="form-control" readonly value="<?= $model->requirements_not_met ?>">
        </div>
        <!-- /Не выполнены требования -->

    </div>

    <div class="row">

        <!-- Мероприятия по устранению несоответствий -->
        <div class="reestr-form--row__units col-md-12">
            <label class="control-label" for="units"><?= 'Мероприятия по устранению несоответствий' ?></label>
            <input type="text" id="units" class="form-control" readonly value="<?= $model->events_elimination ?>">
        </div>
        <!-- /Мероприятия по устранению несоответствий -->

    </div>

    <div class="row">

        <!-- Причина доработкий -->
        <div class="reestr-form--row__units col-md-12">
            <label class="control-label" for="units"><?= 'Причина доработкий' ?></label>
            <input type="text" id="units" class="form-control" readonly value="<?= $model->reason_modification ?>">
        </div>
        <!-- /Причина доработкий -->

    </div>

    <div class="row">

        <!-- Плановый срок -->
        <div class="reestr-form--row__units col-md-3">
            <label class="control-label" for="units"><?= 'Плановый срок' ?></label>
            <input type="text" id="units" class="form-control" readonly value="<?= $model->date_plan ?>">
        </div>
        <!-- /Плановый срок -->

        <!-- Фактичекая дата -->
        <div class="reestr-form--row__units col-md-3">
            <label class="control-label" for="units"><?= 'Фактичекая дата' ?></label>
            <input type="text" id="units" class="form-control" readonly value="<?= $model->date_fact ?>">
        </div>
        <!-- /Фактичекая дата -->

    </div>

    <div class="row">

        <!-- Ответственный -->
        <div class="reestr-form--row__units col-md-4">
            <label class="control-label" for="id_otvetst"><?= $model->getAttributeLabel('id_otvetst') ?></label>
            <input type="text" id="id_otvetst" class="form-control" readonly value="<?= \frontend\modules\elk\models\Card::getFioByCardId($model->id_otvetst) ?>">
        </div>
        <!-- /Ответственный -->

        <!-- Контролирующий -->
        <div class="reestr-form--row__units col-md-4">
            <label class="control-label" for="id_kontrol"><?= $model->getAttributeLabel('id_kontrol') ?></label>
            <input type="text" id="id_kontrol" class="form-control" readonly value="<?= \frontend\modules\elk\models\Card::getFioByCardId($model->id_kontrol) ?>">
        </div>
        <!-- /Контролирующий -->

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
        <?= Html::a('Закрыть', ['reestr/index'], ['class' => 'btn coca_cola_gray']) ?>
    </div>


            <?php ActiveForm::end(); ?>

</div>
