<?php

use yii\bootstrap4\Modal;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\bootstrap4\ButtonDropdown;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel frontend\modules\elk\models\search\DepartmentDataSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Подразделения ЭЛК';
//$this->params['breadcrumbs'][] = ['label' => 'СПИСОК РИСКОВ/УГРОЗ', 'url' => ['/risk/reestr/index']];
//$this->params['breadcrumbs'][] = ['label' => 'СПРАВОЧНИКИ'];
$this->params['breadcrumbs'][] = $this->title;

//выводим поиск

$search = "$('.search-button').click(function(){
	$('.search-form').toggle(1000);
	return false;
});";
$this->registerJs($search);

//показываем кол-во страниц

//$url = "$(document).ready(function(){
//    var url = ($(location).attr('href').lastIndexOf('page')) + 5;
//    if (($(location).attr('href').lastIndexOf('page')) == -1) {
//        $('#record').text('Показать 20');
//    } else {
//        $('#record').text('Показать ' + $(location).attr('href').substring(url));
//    };
//});";
//$this->registerJs($url);

//выводим модальное окно если идет загрузка

$modal = "$(function () {
    $(document).on('click', '.showModalButton', function (e) {
        e.preventDefault();
        var container = $('#modalContent');
        var header = $('#modalHeader');
        container.html('Пожалуйста, подождите. Идет загрузка...');
        // Выводим модальное окно, загружаем данные
        $('#modal').find(header).text($(this).attr('title'));
        $('#modal').modal('show').find(container).load($(this).attr('value'));
        $(\"#modal\").on('hidden.bs.modal', function () {
            $('#modalContent').html('вывод работает');
        });
    });
});";
$this->registerJs($modal);

?>

<div class="department-data-index">
    <?php
    if (Yii::$app->user->can('elk_admin')||Yii::$app->user->can('elk_user_gxk')) {
        echo Html::button('Добавить', [
                'value' => Url::to(['create']),
                'title' => 'Добавить Подразделение ЭЛК',
                'class' => 'showModalButton btn btn-shadow btn-success']);
        }
    ?>

    <?= Html::a('Очистить фильтр',  ['clear-filter','modelClassName' => frontend\modules\elk\models\DepartmentData::className()], ['class' => 'btn btn-shadow btn-danger']) ?>

</div>


    <?php
        Modal::begin([
            'headerOptions' => ['id' => 'modalHeader'],
            'id' => 'modal',
            'size' => 'modal-xl',
            'clientOptions' => [
                'backdrop' => 'static',
                'keyboard' => FALSE,
            ],
        ]);
        echo "<div id='modalContent'></div>";
        Modal::end();
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
           [
                'attribute' => 'emp_department_code',
                'format' => 'raw',
                'value' => function ($data) {
                    return Html::a($data->emp_department_code, '#', [
                        'value' => Url::to(['view', 'id' => $data['id']]),
                        'title' => Yii::t('app', 'Просмотреть Общие данные по подразделению'),
                        'class' => 'showModalButton',
                    ]);
                },
            ],
            'emp_department_type',
            [
                'attribute' => 'emp_department_name',
                'format' => 'raw',
                'value' => function ($data) {
                    return Html::a($data->emp_department_name, '#', [
                        'value' => Url::to(['view', 'id' => $data['id']]),
                        'title' => Yii::t('app', 'Просмотреть Общие данные по подразделению'),
                        'class' => 'showModalButton',
                    ]);
                },
            ],
            'emp_department_full_name',
            [
                'attribute' => 'block',
                'format' => 'raw',
                'filter' => $block,
                'value' => function ($data) {
                    return $data->block ? '<span class="text-danger">Да</span>' : '<span class="text-success">Нет</span>';
                }
            ],
//            'active',
            [
                'class' => 'kartik\grid\ActionColumn',
                'contentOptions' => [
                    'style' => 'column-main-grid__10'
                ],
                'buttons' => [
                    'all' => function ($url, $model, $key) {
                        return '<div class="btn-group">' . ButtonDropdown::widget([
                            'label' => '...',
                            'options' => ['class' => 'btn-action btn-shadow btn-default'],
                            'dropdown' => [
                                'options' => ['class' => 'dropdown-menu my_width_ddm'],
                                'items' => [
                                    [
                                        'label' => 'Редактировать',
                                        'url' => '#',
                                        'linkOptions' => [
                                            'value' => Url::to(['update', 'id' => $key]),
                                            'title' => 'Редактировать Подразделения ЭЛК',
                                            'class' => 'showModalButton',
                                        ],
                                        'disabled' => (Yii::$app->user->can('elk_admin')||Yii::$app->user->can('elk_user_gxk')) ? false : true,
                                    ],
                                ],
                            ],
                        ]) . '</div>';
                    },
                ],
                'template' => '{all}'
            ],
        ],
    ]); ?>
</div>
