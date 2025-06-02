<?php
use yii\bootstrap4\Nav;
use yii\helpers\Url;

$isAdmin = Yii::$app->user->can('equ_admin');
$items = [
    [
        'label' => Yii::t('eav', 'Attribute Categories'),
        'url' => Url::toRoute('/eav-equ/attribute-category'),
    ],
    [
        'label' => Yii::t('eav', 'Attribute Types'),
        'url' => Url::toRoute('/eav-equ/attribute-type'),
    ],
    [
        'label' => Yii::t('eav', 'Attributes'),
        'url' => Url::toRoute('/eav-equ/attribute'),
    ]
];
?>
<div class="top-menu">
    <?php echo \yii\bootstrap4\ButtonDropdown::widget([
        'label' => Yii::t('eav', 'Attributes'),
        'options' => [
            'class' => 'btn btn-xs btn-default',
            'id' => 'btn-checkbox-actions'
        ],
        'dropdown' => [
            'options' => ['class' => 'dropdown-menu'],
            'encodeLabels' => false,
            'items' => $items
        ]
    ]); ?>
</div>