<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\MediaContent */
/* @var $is_update boolean */

$this->title = Yii::t('app', 'Create');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Media Content'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="media-content-create">

    <?= $this->render('_form', [
        'model' => $model,
        'is_update' => false,
    ]) ?>

</div>
