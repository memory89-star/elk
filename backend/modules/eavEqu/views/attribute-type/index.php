<?php
/**
 * @var $this yii\web\View
 * @var $dataProvider yii\data\ActiveDataProvider
 * @var $entityName string
 */
use kartik\grid\GridView;
use yii\helpers\Html;

$objectName = Yii::t('eav', 'Attribute Type');
$objectsName = Yii::t('eav', 'Attribute Types');
$entityName = Yii::t('eav', $entityName);

$this->title = $entityName . ' ' . $objectsName;
$this->params['breadcrumbs'][] = $this->title;
$columns = [
    'id',
    'name',
    'handlerClass',
    'storeType',
    [
        'class' => 'kartik\grid\ActionColumn',
        'template' => '{update} {delete}',
    ],
];
?>
<div class="eav-attribute-index">
    <?php echo $toolbar = \common\widgets\GridToolbar::widget([
        'gridColumns' => $columns,
        'dataProvider' => $dataProvider,
        'content' => \Yii::$app->view->renderFile('@backend/modules/eavEqu/views/layouts/top-menu.php'),
        'createTitle' => Yii::t('eav', 'Create'),
        'createUrl' => ['create'],
    ]); ?>
    <p>
        <?= Html::a(Yii::t('eav', 'Create'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
                'dataProvider' => $dataProvider,
                //'filterModel' => $searchModel,
                'columns' => $columns,
            ]); ?>
</div>
