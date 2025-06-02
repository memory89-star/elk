<?php

/** @var \yii\web\View $this */
/** @var string $directoryAsset */

use yii\helpers\Html;
use yii\bootstrap4\Nav;
use yii\bootstrap4\NavBar;

$userModule = Yii::$app->modules['user'];
?>

<?php
NavBar::begin([
    'id' => 'top-navbar',
    'innerContainerOptions' => ['class' => 'container-fluid'],
    'options' => [
        'class' => 'main-header navbar navbar-expand navbar-dark navbar-indigo',
    ],
]);

$menuLeftItems[] = '<li class="nav-item">'
    . Html::tag('a', '<i class="fas fa-bars"></i>', [
        'class' => 'nav-link sidebar-toggle',
        'data-widget' => 'pushmenu',
        'href' => '#'
    ]) .
    '</li>';

$menuLeftItems[] = ['label' => Yii::t('app', 'Главная'), 'url' => ['/site/index']];

$menuLeftItems[] = ['label' => Yii::t('app', 'Frontend'), 'url' => Yii::$app->urlManagerFrontEnd->baseUrl];

if (Yii::$app->user->isGuest) {
    $menuRightItems[] = ['label' => Yii::t('app', 'Signup'), 'url' => ['/site/signup'], 'visible' => $userModule->enableRegistration];
    $menuRightItems[] = ['label' => Yii::t('app', 'Login'), 'url' => ['/user/login']];
};

$menuRightItems[] = '<li class="nav-item">'
    . Html::a('<i class="fas fa-user"></i> Профиль',
        Yii::$app->urlManagerFrontEnd->baseUrl . '/userProfile',
        ['class' => 'nav-link']
    )
    . '</li>';

$currentUser = Yii::$app->user->identity;

$menuRightItems[] = '<li class="nav-item">'
    . Html::a('<i class="fas fa-power-off"></i> Выход (' . (isset($currentUser->profile) ? $currentUser->profile->getFullName() : null) . ')',
        ['/site/logout'],
        [
            'class' => 'nav-link',
            'data-method' => 'post'
        ]
    )
    . '</li>';

echo Nav::widget([
    'options' => ['class' => 'nav navbar-nav'],
    'items' => $menuLeftItems,
]);

echo Nav::widget([
    'options' => ['class' => 'navbar-nav ml-auto'],
    'items' => $menuRightItems,
]);

NavBar::end();
?>
