<?php

/** @var \yii\web\View $this */
/** @var string $directoryAsset */

use yii\helpers\Url;
$isAdmin = Yii::$app->user->can('admin') || Yii::$app->user->can('developer');
$isSecurityAdmin = Yii::$app->user->can('security_admin');
$isSupportOperator = Yii::$app->user->can('support_operator');
?>

<aside class="main-sidebar sidebar-dark-indigo elevation-4">
    <?= \yii\helpers\Html::a('<img class="brand-image img-circle elevation-3" src="' . ($directoryAsset . '/img/AdminLTELogo.png') . '" alt="APP"><span class="brand-text font-weight-light">' . Yii::t('app', 'DIT admin panel') . '</span>', Yii::$app->homeUrl, ['class' => 'brand-link']) ?>
    <div class="sidebar">

        <nav class="mt-2">
            <?= dmstr\adminlte\widgets\Menu::widget(
                [
                    'options' => ['class' => 'nav nav-pills nav-sidebar flex-column', 'data-widget' => 'treeview'],
                    'items' => [
                        ['label' => 'RBAC Manager', 'url' => Url::toRoute('admin/default'), 'icon' => 'user', 'visible' => $isAdmin || $isSecurityAdmin || $isSupportOperator],
                        ['label' => 'Gii', 'url' => Url::toRoute('/gii'), 'icon' => 'wrench', 'visible' => $isAdmin],
                        ['label' => 'Git', 'url' => Url::toRoute('/git'), 'icon' => 'random', 'visible' => $isAdmin],
                        ['label' => Yii::t('app', 'Scheduler Jobs'), 'url' => Url::toRoute('/scheduler-jobs'), 'icon' => 'clock', 'visible' => $isAdmin || $isSecurityAdmin],
                        ['label' => Yii::t('app', 'Debug'), 'url' => Url::toRoute('/debug'), 'icon' => 'tachometer-alt', 'visible' => $isAdmin],
                        ['label' => Yii::t('app', 'Users'), 'url' => Url::toRoute('/user-manager'), 'icon' => 'cat', 'visible' => $isAdmin || $isSecurityAdmin || $isSupportOperator],
                        ['label' => Yii::t('app', 'EAV'), 'url' => Url::toRoute('/eav-equ'), 'icon' => 'sitemap', 'visible' => $isAdmin],
                        ['label' => Yii::t('app', 'Media Content'), 'url' => Url::toRoute('/media-content'), 'icon' => 'play-circle', 'visible' => $isAdmin || $isSecurityAdmin],
                    ],
                ]
            ) ?>
        </nav>

    </div>

</aside>
