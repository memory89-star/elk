<?php
/**
 * @var $this yii\web\View
 * @var $model \yarcode\eav\models\Attribute
 * @var $entityName string
 * @var $typesQuery \yii\db\ActiveQuery
 */
use yii\helpers\Html;

$objectsName = Yii::t('eav', 'Attribute Types');
$entityName = Yii::t('eav', $entityName);

$this->title = $entityName . ' ' . $objectsName;
$this->params['breadcrumbs'][] = ['label' => $entityName . ' ' . Yii::t('eav', 'Attributes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('eav', 'Create');
?>
<div class="eav-attribute-create">
    <?= $this->render('_form', [
        'model' => $model,
        //'typesQuery' => $typesQuery,
    ]) ?>
</div>
