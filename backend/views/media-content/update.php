<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\MediaContent */
/* @var $is_update boolean */

$this->title = Yii::t('app', 'Update Media Content: {name}', [
    'name' => $model->id,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Media Content'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="media-content-update">

    <?= $this->render('_form', [
        'model' => $model,
        'is_update' => true,
    ]) ?>

</div>
