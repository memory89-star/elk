<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model frontend\modules\elk\models\search\EventsSearch */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="events-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>



    <div class="form-group">
        <?= Html::submitButton('Поиск', ['class' => 'btn coca_cola']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
