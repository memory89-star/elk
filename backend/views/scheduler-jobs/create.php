<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\SchedulerJobs */
/* @var $users \common\modules\userProfile\models\UserProfile */
/* @var $frequency_types array */
/* @var $status array */

$this->title = Yii::t('app', 'Create Scheduler Jobs');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Scheduler Jobs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scheduler-jobs-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'users' => $users,
        'frequency_types' => $frequency_types,
        'status' => $status,
    ]) ?>

</div>
