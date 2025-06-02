<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use common\models\User;

/* @var $this yii\web\View */
/* @var $user common\models\User */
/* @var $form yii\widgets\ActiveForm */
/* @var $products array */
/* @var $profile \common\modules\userProfile\models\UserProfile */
/* @var $cards array */


$baseFrontendUrl = Yii::$app->urlManagerFrontEnd->baseUrl;

/*echo $this->render('@frontend/views/site/model_errors', [
    'model' => $user,
    'excludeAttributes' => ['username', 'plainPassword', 'plainPasswordRepeat', 'second_name', 'first_name', 'third_name']
]);*/

$isUpdate = $user->scenario == User::SCENARIO_UPDATE;
$formattedDate = Yii::$app->formatter->asDateTime($user->blocked_at, 'php:d.m.Y H:i');

$js = <<<SCRIPT
yii.script.baseFrontendUrl = '$baseFrontendUrl';
yii.script.initRegisterUserForm();
SCRIPT;
$this->registerJs($js);
?>

<div class="database-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($user, 'username')->textInput(['maxlength' => true]) ?>

                    <?php if ($isUpdate): ?>
                    <?= $form->field($user, 'email')->textInput(/*['readOnly' => true]*/) ?>
                    <?php endif; ?>

                    <?php if (!$isUpdate): ?>
                        <?= $form->field($user, 'email')->textInput() ?>
                    <?php endif; ?>

                    <?= $form->field($profile, 'stabnum')->textInput() ?>

                    <?= $form->field($profile, 'card_id')->widget(Select2::class, [
                        'id' => 'select-userprofile-card_id',
                        'theme' => Select2::THEME_KRAJEE_BS4,
                        'data' => $cards,
                        'options' => ['placeholder' => Yii::t('hd', 'Select card...')],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],

                    ]); ?>

                    <?php if ($isUpdate): ?>
                        <?= $form->field($profile, 'department_id')->widget(Select2::class, [
                            'theme' => Select2::THEME_KRAJEE_BS4,
                            'data' => $departments,
                            'options' => ['placeholder' => Yii::t('hd', 'Select department...')],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ]); ?>
                    <?php endif; ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($profile, 'secondname')->textInput() ?>

                    <?= $form->field($profile, 'firstname')->textInput() ?>

                    <?= $form->field($profile, 'thirdname')->textInput() ?>

                    <?= $form->field($profile, 'phone')->textInput() ?>

                    <?php if ($isUpdate): ?>
                        <?= $form->field($user, 'blocked_at')->widget(\kartik\widgets\DateTimePicker::className(), [
                            'language' => 'ru',
                            'options' => [
                                 'value' => $formattedDate,
                                 'placeholder' => Yii::t('app', 'Дата блокировки'),
                                ],
                            'pluginOptions' => [
                                 'autoclose' => true,
                                 'format' => 'dd.mm.yyyy HH:ii',
                                 'todayHighlight' => true,
                                 'todayBtn' => true,
                            ]
                        ]); ?>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <?= Html::submitButton(Yii::t('app', 'Сохранить'), ['class' => $user->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                </div>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>

