<?php
/**
 * @var $this yii\web\View
 * @var $attribute \yarcode\eav\models\Attribute
 * @var $entityName string
 */
use kartik\grid\GridView;
use yii\helpers\Html;

$objectName = Yii::t('eav', 'Attribute Options');
$objectsName = Yii::t('eav', 'Attributes Options');
$entityName = Yii::t('eav', $entityName);

$this->title = $entityName . ' ' . $objectsName;
$this->params['breadcrumbs'][] = ['label' => $entityName . ' ' . $objectName, 'url' => ['attribute/index']];
$this->params['breadcrumbs'][] = ['label' => $attribute->name, 'url' => ['attribute/update', 'id' => $attribute->id]];
$this->params['breadcrumbs'][] = 'Options';
$columns = [
    'id',
    'value',
    [
        'class' => 'kartik\grid\ActionColumn',
        'template' => '{update}{delete}',
        'headerOptions' => ['style' => 'width: 48px'],
    ]
];
?>
<div class="eav-attribute-options">
    <div class="eav-attribute-index">
        <?php echo $toolbar = \common\widgets\GridToolbar::widget([
            'gridColumns' => $columns,
            'dataProvider' => $dataProvider,
            'content' => \Yii::$app->view->renderFile('@backend/modules/eavEqu/views/layouts/top-menu.php'),
            'createTitle' => Yii::t('eav', 'Create'),
            'createUrl' => ['create', 'attributeId' => $attribute->id],
        ]); ?>

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => $columns,
        ]) ?>
    </div>
</div>
