<?php

use kartik\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\MediaContentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Media Content');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="media-content-index">

    <p>
        <?= Html::a(Yii::t('app', 'Create'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <div class="box">
        <div class="box-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    'id',
                    [
                        'attribute' => 'created_at',
                        'label' => Yii::t('app', 'Created At'),
                        'value' => function ($model) {
                            return Yii::$app->formatter->asDatetime($model->created_at);
                        },
                    ],
                    'original_name',
                    'file_type',
                    'file_ext',
                    //'file_size',
                    //'description:ntext',
                    //'tags',

                    ['class' => 'kartik\grid\ActionColumn'],
                ],
            ]); ?>
        </div>
    </div>

</div>
