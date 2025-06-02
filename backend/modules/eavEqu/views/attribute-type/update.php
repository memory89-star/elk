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
$title = Yii::t('eav', $entityName) . ' ' . Yii::t('eav', 'Attributes');
$this->title = $title;
$this->params['breadcrumbs'][] = ['label' => $title, 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('eav', 'Update');
?>
<div class="eav-attribute-update">
    <?= $this->render('_form', [
        'model' => $model,
        //'typesQuery' => $typesQuery,
    ]) ?>
</div>
