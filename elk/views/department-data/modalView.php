<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model frontend\modules\elk\models\DepartmentData */
/* @var $form yii\widgets\ActiveForm */

?>
<div class="card">
    <div class="card-body">
        <div class="department-data-form">
            <?php $form = ActiveForm::begin([
                'id' => 'modal-department-data-form',
                'enableAjaxValidation' => true,
            ]); ?>

            <div class="department-data--row row">
                <!-- Подразделение -->
                <div class="department-form--row__emp_department_code col-md-4">
                    <div class="form-group">
                        <label class="control-label" for="emp_department_code"><?= 'Подразделение' ?></label>
                        <input class="form-control" type="text" id="emp_department_code" readonly value="<?= $model->emp_department_code ?>">
                    </div>
                </div>
                <!-- /Подразделение -->

                <!-- Краткое наименование подразделения -->
                <div class="department-form--row__emp_department_name col-md-8">
                    <div class="form-group">
                        <label class="control-label" for="emp_department_name"><?= 'Подразделение' ?></label>
                        <input class="form-control" type="text" id="emp_department_name" readonly value="<?= $model->emp_department_name ?>">
                    </div>
                </div>
                <!-- /Краткое наименование подразделения -->
            </div>

            <div class="department-data--row row">
                <!-- Полное наименование подразделения -->
                <div class="department-form--row__emp_department_full_name col-md-12">
                    <div class="form-group">
                        <label class="control-label" for="emp_department_full_name"><?= 'Полное наименование подразделения' ?></label>
                        <textarea class="form-control" type="text" id="emp_department_full_name" readonly><?= $model->emp_department_full_name ?></textarea>
                    </div>
                </div>
                <!-- /Полное наименование подразделения -->
            </div>

            <div class="department-data--row row">
                <!-- Номер документа -->
                <div class="department-form--row__doc_num_max col-md-6">
                    <?= $form->field($model, 'doc_num_max')->textInput(['maxlength' => true, 'disabled' => true, ]) ?>
                </div>
                <!-- /Номер документа -->

                <!-- Блокировка -->
                <div class="department-form--row__block col-md-6">
                    <div class="form-group">
                        <label class="control-label" for="block"><?= 'Блокировка' ?></label>
                        <input class="form-control" type="text" id="block" readonly value="<?= $model->setBlock($model->block) ?>">
                    </div>
                </div>
                <!-- /Блокировка -->
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="control-label" for="user_first"><?= 'Создано' ?></label>
                        <div style="display: flex; align-items: center; gap: 1rem;">
                            <input style="width: 60%;" type="text" id="user_first" class="form-control" readonly value="<?= $user_last ?>">
                            <input style="width: 40%;" type="datetime" id="date_time_create" class="form-control" readonly value="<?= $date_time_create ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="user_last"><?= 'Изменено' ?></label>
                        <div style="display: flex; align-items: center; gap: 1rem;">
                            <input style="width: 60%;" type="text" id="user_last" class="form-control" readonly value="<?= $user_first ?>">
                            <input style="width: 40%;" type="datetime" id="date_time_update" class="form-control" readonly value="<?= $date_time_update ?>">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Кнопки -->
            <div class="button-group">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
            </div>
            <!-- /Кнопки -->

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
