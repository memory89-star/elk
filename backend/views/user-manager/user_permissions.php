<?php

use common\models\search\UserSearch;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $availableIndicators \frontend\models\dashboard\Indicator[] */
/* @var $notAvailableIndicators \frontend\models\dashboard\Indicator[] */

$this->title = Yii::t('app', 'Разрешения');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Пользователи'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

var_dump($notAvailableIndicators);
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>



</div>