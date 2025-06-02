<?php
/**
 * @var $this yii\web\View
 * @var $attribute \yarcode\eav\models\Attribute
 * @var $model \yarcode\eav\models\AttributeOption
 * @var $entityName string
 */
use yii\helpers\Html;

$objectsName = Yii::t('eav', 'Attributes Options');
$entityName = Yii::t('eav', $entityName);

$this->title = $entityName . ' ' . $objectsName;
$this->params['breadcrumbs'][] = ['label' => $entityName . ' ' . Yii::t('eav', 'Attributes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $attribute->name, 'url' => ['attribute/update', 'id' => $model->id]];
$this->params['breadcrumbs'][] = ['label' => 'Options', 'url' => ['update', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('eav', 'Update');
?>
<div class="eav-attribute-option-update">
    <?= $this->render('_form', [
        'model' => $model,
        'attribute' => $attribute,
    ]) ?>
</div>
