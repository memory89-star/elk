<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use dektrium\user\models\LoginForm;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;


/**
 * @var yii\web\View $this
 * @var dektrium\user\models\LoginForm $model
 * @var dektrium\user\Module $module
 */

$this->title = Yii::t('user', 'Sign in');
$this->params['breadcrumbs'][] = $this->title;

$fieldOptions1 = [
    'options' => ['class' => 'form-group has-feedback'],
    'inputTemplate' => '<div class="input-group mb-3">{input}
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-envelope"></span>
                </div>
            </div>
        </div>'
];

$fieldOptions2 = [
    'options' => ['class' => 'form-group has-feedback'],
    'inputTemplate' => '<div class="input-group mb-3">{input}
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-lock"></span>
                </div>
            </div>
        </div>'
];
?>

<?= $this->render('/_alert', ['module' => Yii::$app->getModule('user')]) ?>

<div class="card elevation-4">
    <div class="card-body login-card-body">
        <p class="login-box-msg"><?= Yii::t('app', 'Sign in to start your session') ?></p>

        <?php $form = ActiveForm::begin([
            'id' => 'login-form',
           /*'enableAjaxValidation' => true,
            'enableClientValidation' => false,
            'validateOnBlur' => false,
            'validateOnType' => false,
            'validateOnChange' => false,*/
        ]) ?>

        <?php if ($module->debug): ?>
            <?= $form
                ->field($model, 'login', $fieldOptions1)
                ->label(false)
                ->textInput(['placeholder' => $model->getAttributeLabel('login')])
                ->dropDownList(LoginForm::loginList())
            ?>

        <?php else: ?>

            <?= $form
                ->field($model, 'login', $fieldOptions1)
                ->label(false)
                ->textInput(['placeholder' => $model->getAttributeLabel('login')])
            ?>

        <?php endif ?>

        <?php if ($module->debug): ?>
            <div class="alert alert-warning">
                <?= Yii::t('user', 'Password is not necessary because the module is in DEBUG mode.'); ?>
            </div>
        <?php else: ?>
            <?= $form
                ->field($model, 'password', $fieldOptions2)
                ->label(false)
                ->passwordInput(['placeholder' => $model->getAttributeLabel('password')])
            ?>
        <?php endif ?>

        <div class="row">
            <div class="col-6">
                <div class="icheck-primary">
                    <?= $form->field($model, 'rememberMe')->checkbox(['tabindex' => '3']) ?>
                </div>
            </div>

            <div class="col-6">
                <?php if (!isset(Yii::$app->params['enableLdapAuth']) || !Yii::$app->params['enableLdapAuth']): ?>
                    <?= Html::a(
                        Yii::t('user', 'Forgot password?'),
                        ['/user/recovery/request'],
                        ['tabindex' => '5']
                    ) ?>
                <?php endif; ?>
            </div>

            <?= Html::submitButton(
                Yii::t('user', 'Sign in'),
                ['class' => 'btn btn-primary btn-block', 'name' => 'login-button', 'tabindex' => '4']
            ) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>




