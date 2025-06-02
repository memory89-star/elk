<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\modules\userProfile\models\UserProfile;
use backend\models\SchedulerJobs;

/* @var $this yii\web\View */
/* @var $model backend\models\SchedulerJobs */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Scheduler Jobs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

$attributes = [
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
            return Html::encode(UserProfile::getShortNameFromFull($data->userProfile->firstname, $data->userProfile->secondname, $data->userProfile->thirdname));
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
    [
        'attribute' => 'priority',
        'label' => Yii::t('app', 'Priority'),
    ],
    [
        'attribute' => 'description',
        'label' => Yii::t('app', 'Description'),
    ],
];
?>
<div class="scheduler-jobs-view">

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>
    <div class="box">
        <div class="box-body">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => $attributes
            ]) ?>
        </div>
    </div>

</div>
