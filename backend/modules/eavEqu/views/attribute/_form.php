<?php
/**
 * @var $this yii\web\View
 * @var $model \yarcode\eav\models\Attribute
 * @var $form yii\widgets\ActiveForm
 * @var $typesQuery \yii\db\ActiveQuery
 * @var $categoriesQuery \yii\db\ActiveQuery
 */
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use \frontend\modules\equipment\models\eav\AttributeCategory;

$categoriesQuery = AttributeCategory::find();
?>
<div class="eav-attribute-form">
    <?php
    $this->beginContent('@backend/modules/eavEqu/views/layouts/top-menu.php');
    $this->endContent();
    ?>
    <?php $form = ActiveForm::begin(); ?>
    <div class="box">
        <div class="box-body">
            <?= $form->field($model, 'typeId')->dropDownList(ArrayHelper::map($typesQuery->all(), 'id', 'name')) ?>
            <?= $form->field($model, 'categoryId')->dropDownList(ArrayHelper::map($categoriesQuery->all(), 'id', 'name')) ?>
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'required')->checkbox() ?>
            <?= $form->field($model, 'defaultValue')->textInput() ?>
            <?= $form->field($model, 'defaultOptionId')->dropDownList(
                \yii\helpers\ArrayHelper::map($model->options, 'id', 'value'),
                ['prompt' => 'Please select']
            ) ?>
            <div class="form-group">
                <?= Html::submitButton($model->isNewRecord ? Yii::t('eav', 'Create') : Yii::t('eav', 'Update'),
                    ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                <?= Html::a(Yii::t('eav', 'Cancel'), ['index'], ['class' => 'btn btn-default']) ?>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
