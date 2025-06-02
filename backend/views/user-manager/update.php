<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */
/* @var $profile common\modules\userProfile\models\UserProfile */
/* @var $departments frontend\modules\helpdesk\models\Department */
/* @var $cards \frontend\modules\employee\models\Card */

$this->title = Yii::t('app', 'Изменить {modelClass}: ', [
    'modelClass' => 'пользователя',
]) . $user->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Пользователи'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-update">

    <?= $this->render('_form', [
        'user' => $user,
        'profile' => $profile,
        'departments' => $departments,
        'cards' => $cards,
    ]) ?>

</div>
