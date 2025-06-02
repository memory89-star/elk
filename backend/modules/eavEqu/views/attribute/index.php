<?php
/**
 * @var $this yii\web\View
 * @var $dataProvider yii\data\ActiveDataProvider
 * @var $entityName string
 */
use kartik\grid\GridView;
use yii\helpers\Html;

$objectName = Yii::t('eav', 'Attribute');
$objectsName = Yii::t('eav', 'Attributes');
$entityName = Yii::t('eav', $entityName);

$this->title = $entityName . ' ' . $objectsName;
$this->params['breadcrumbs'][] = $this->title;
$entityName = Yii::t('eav', $entityName);
$columns = [
    'id',
    'name',
    'category.name:text:Category',
    'type.name:text:Type',
    [
        'header' => 'Options',
        'class' => 'kartik\grid\ActionColumn',
        'template' => '{options}',
        'buttons' => [
            'options' => function ($url, $model, $key) {
                /** @var $model \yarcode\eav\models\Attribute */
                if (!$model->type->hasOptions()) {
                    return '';
                }
                $options = [
                    'title' => 'Edit attribute options',
                    'data-pjax' => '0',
                ];
                return Html::a('<span class="fa fa-list-alt"></span>',
                    ['attribute-option/index', 'attributeId' => $model->id], $options);
            }
        ]
    ],
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

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => $columns,
    ]); ?>
</div>
