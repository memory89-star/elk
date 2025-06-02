<?php
use yii\helpers\Url;
use yii\web\View;

/* @var $this yii\web\View */

$this->title = Yii::t('app', 'Админ панель');
?>

<div class="site-index">

    <div class="jumbotron text-center">
        <h1><?= Yii::t('app', 'Hi! I\'m glad to see you!') ?></h1>

        <p class="lead"><?= Yii::t('app', 'You are in the admin panel. Please, be careful...') ?></p>
    </div>

    <div class="row text-center" >
        <div class="col-md-4 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-blue">
                <div class="inner">
                    <h3>
                        Фронтенд
                    </h3>

                    <p>
                        Go to Frontend
                    </p>
                </div>
                <div class="icon">
                    <i class="ion ion-home"></i>
                </div>
                <a href="<?= Url::to('/frontend/web') ?>" class="small-box-footer">
                    Домашняя страница <i class="fa fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <!-- ./col -->

        <div class="col-md-4 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-aqua">
                <div class="inner">
                    <h3>
                        Пользователи
                    </h3>

                    <p>
                        Users
                    </p>
                </div>
                <div class="icon">
                    <i class="ion ion-person"></i>
                </div>
                <a href="<?= \yii\helpers\Url::to(['/user-manager']) ?>" class="small-box-footer">
                    Управление <i class="fa fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <!-- ./col -->

        <div class="col-md-4 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-cyan">
                <div class="inner">
                    <h3>
                        Модули
                    </h3>

                    <p>
                        Modules
                    </p>
                </div>
                <div class="icon">
                    <i class="ion ion-stats-bars"></i>
                </div>
                <a href="<?= \yii\helpers\Url::to(['/debug']) ?>" class="small-box-footer">
                    Отладка <i class="fa fa-arrow-circle-right"></i>
                </a>
            </div>

        </div>

    </div>
    </div>
</div>


