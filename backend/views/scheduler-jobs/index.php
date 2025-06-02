<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use common\modules\userProfile\models\UserProfile;
use backend\models\SchedulerJobs;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\SchedulerJobsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Scheduler Jobs');
$this->params['breadcrumbs'][] = $this->title;

$columns = [
    [
        'class' => 'yii\grid\SerialColumn'
    ],
    [
        'attribute' => 'id',
        'label' => Yii::t('app', 'id')
    ],
    [
        'attribute' => 'module',
        'label' => Yii::t('app', 'Module')
    ],
    [
        'attribute' => 'job_action',
        'label' => Yii::t('app', 'Job Action')
    ],
    'description',
    [
        'attribute' => 'status',
        'label' => Yii::t('app', 'Status'),
        'value' => function ($model) {
            return $model['status'] == SchedulerJobs::STATUS_INACTIVE ? Yii::t('app', 'Inactive') : Yii::t('app', 'Active');
        },
        'filter' => [
            SchedulerJobs::STATUS_INACTIVE => Yii::t('app', 'Inactive'),
            SchedulerJobs::STATUS_ACTIVE => Yii::t('app', 'Active'),
        ]
    ],
    [
        'attribute' => 'frequency_type',
        'label' => Yii::t('app', 'Frequency Type'),
        'filter' => [
            'minutely' => Yii::t('app', 'Minutely'),
            'hourly' => Yii::t('app', 'Hourly'),
            'daily' => Yii::t('app', 'Daily'),
            'weekly' => Yii::t('app', 'Weekly'),
            'monthly' => Yii::t('app', 'Monthly'),
        ]
    ],
    [
        'attribute' => 'frequency',
        'label' => Yii::t('app', 'Frequency'),
    ],
    [
        'attribute' => 'notify_user_id',
        'label' => Yii::t('app', 'Notify User Id'),
        'value' => function ($data) {
            return Html::encode(UserProfile::getShortNameFromFull($data['firstname'], $data['secondname'], $data['thirdname']));
        },
    ],
    [
        'attribute' => 'is_notify',
        'label' => Yii::t('app', 'Is Notify'),
        'format' => 'raw',
        'value' => function ($data) {
            return ($data['is_notify'] ? Yii::t('app', 'Yes') : Yii::t('app', 'No'));
        }
    ],
    'priority',
    [
        'class' => 'kartik\grid\ActionColumn'
    ],
];
?>
<div class="scheduler-jobs-index">

    <p>
        <?= Html::a(Yii::t('app', 'Create Scheduler Jobs'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => $columns,
    ]); ?>
</div>
