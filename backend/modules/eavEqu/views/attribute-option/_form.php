<?php
/**
 * @var $this yii\web\View
 * @var $model \yarcode\eav\models\Attribute
 * @var $attribute \yarcode\eav\models\Attribute
 */
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>
<div class="eav-attribute-form">
    <?php
    $this->beginContent('@backend/modules/eavEqu/views/layouts/top-menu.php');
    $this->endContent();
    ?>
    <?php $form = ActiveForm::begin(); ?>
    <div class="box">
        <div class="box-body">
            <?= $form->field($model, 'value')->textInput() ?>
            <div class="form-group">
                <?= Html::submitButton($model->isNewRecord ? Yii::t('eav', 'Create') : Yii::t('eav', 'Update'),
                    ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                <?= Html::a(Yii::t('eav', 'Cancel'), ['index', 'attributeId' => $attribute->id], ['class' => 'btn btn-default']) ?>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
