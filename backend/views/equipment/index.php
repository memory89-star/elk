<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\modules\equipment\models\EquipmentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Equipments';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="equipment-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Equipment', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            'description',
            'equipment_type_id',
            'equipment_type',
            //'release_date',
            //'factory_number',
            //'passport_num',
            //'release_year',
            //'baan_status_id',
            //'baan_status_name',
            //'responsible_card_id',
            //'responsible_stabnum',
            //'responsible_name',
            //'department_id',
            //'dep_code',
            //'dep_name',

            ['class' => 'kartik\grid\ActionColumn'],
        ],
    ]); ?>


</div>
