<?php

use yii\bootstrap4\Breadcrumbs;
use dmstr\adminlte\widgets\Alert;
use yii\helpers\Html;

?>
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <?php if (isset($this->blocks['content-header'])) { ?>
                        <h1><?= $this->blocks['content-header']; ?></h1>
                    <?php } else { ?>
                        <h1>
                            <?php
                            if ($this->title !== null) {
                                echo \yii\helpers\Html::encode($this->title);
                            } else {
                                echo \yii\helpers\Inflector::camel2words(
                                    \yii\helpers\Inflector::id2camel($this->context->module->id)
                                );
                                echo ($this->context->module->id !== \Yii::$app->id) ? '<small>Module</small>' : '';
                            } ?>
                        </h1>
                    <?php } ?>
                </div>

                <div class="col-sm-6">
                    <?= Breadcrumbs::widget([
                        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                        'options' => [
                            'class' => 'float-sm-right'
                        ]
                    ]); ?>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <?= Alert::widget(); ?>
        <?= $content ?>
    </section>
</div>

<footer class="main-footer">
    <div class="float-right d-none d-sm-block">
        <b>Версия</b> 1.1
    </div>
    <strong>
        <?= Yii::t('app', 'ФГУП ГХК') ?>
        <?= ' - ' . Html::encode(Yii::t('app', Yii::$app->name)) . ' ' ?>
    </strong>
</footer>
