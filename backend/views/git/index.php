<?php

/* @var $this yii\web\View */
/* @var $status array */
/* @var $cliResult array */
/* @var CLIException $commandException array */
/* @var $lastTag array */

use common\exceptions\CLIException;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\form\ActiveForm;

$this->title = Yii::t('app', 'Git');

$this->params['breadcrumbs'][] = $this->title;

$statusText = implode("\n", $status);
$summaryText = implode("\n", $summary);
$cliResultText = ($cliResult ? implode("\n", $cliResult) : null);
?>
<div class="site-index">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-xs-12">
            <?= Html::submitButton('<span class="glyphicon glyphicon-cloud-download"></span> ' . Yii::t('app', 'Git Pull'), [
                'name' => 'submit-button',
                'value' => 'git-pull',
                'class' => 'btn btn-success',
                'data-confirm' => Yii::t('app', 'Вы действительно хотите выполнить git pull?')
            ]) ?>

            <?= Html::submitButton('<span class="glyphicon glyphicon-chevron-up"></span> ' . Yii::t('app', 'Migrate Up'), [
                'name' => 'submit-button',
                'value' => 'migrate-up',
                'class' => 'btn btn-info',
                'data-confirm' => Yii::t('app', 'Вы действительно хотите выполнить migrate/up?')
            ]) ?>

        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-md-8">
            <?= $form->field($model, 'console_command')->textarea(['style' => [
                'width' => '100%',
                'height' => '40px'
            ]]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 pb-3">
            <?= Html::submitButton('<span class="glyphicon glyphicon-console"></span> ' . Yii::t('app', 'Run Console Command'), [
                'name' => 'submit-button',
                'value' => 'console-command',
                'class' => 'btn btn-danger',
                'data-confirm' => Yii::t('app', 'Вы действительно хотите выполнить команду?')
            ]) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

    <?php if ($commandException): ?>
        <div class="row margin-top-15">
            <div class="col-xs-12">
                <label class="control-label text-danger"
                       for="git-status"><?= Yii::t('app', 'Execution error') ?></label>
                <p class="text-danger"><?= Yii::t('app', 'Code: {0}', [$commandException->getCode()]) ?></p>
                <pre class="cli-output text-danger" id="git-status"><?= $commandException->getStringOutput() ?></pre>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($cliResult): ?>
        <div class="row margin-top-15 pb-3">
            <div class="col-xs-12">
                <label class="control-label text-success"
                       for="git-status"><?= Yii::t('app', 'Success execution') ?></label>
                <pre class="cli-output" id="git-status"><?= $cliResultText ?></pre>
            </div>
        </div>
    <?php endif; ?>

    <p><?= Html::encode(Yii::t('app', 'Version ')) ?><b><?= Html::encode($lastTag[0]) ?></b></p>
    <div class="row margin-top-15">
        <div class="col-xs-12">
            <label class="control-label" for="git-status"><?= Yii::t('app', 'Git Status') ?></label>
            <pre class="cli-output" id="git-status"><?= $statusText ?></pre>
        </div>
    </div>

    <div class="row margin-top-15">
        <div class="col-xs-12">
            <label class="control-label" for="git-summary"><?= Yii::t('app', 'Git Summary') ?></label>
            <pre class="cli-output" id="git-summary"><?= $summaryText ?></pre>
        </div>
    </div>
</div>
