<?php

use frontend\modules\elk\models\Discrepancy;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use yii\bootstrap4\ButtonDropdown;
use yii\bootstrap4\Modal;
use kartik\select2\Select2;

$this->title = 'Причины несоответствия';
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

$model = new Discrepancy();
?>

<?php
$add_events = (Yii::$app->user->can('elk_admin')||Yii::$app->user->can('elk_user_dep')||Yii::$app->user->can('elk_user_gxk'));

?>

<div class="kod-index">

    <h4>
        <?='Несоответствие: '?> <b>  <?= $id_reestr[$id] ?>  </b>
    </h4>
    <h4>
        <?='Код объекта ЛК: '?> <b>  <?= $model->getKodInd($id) ?>  </b>
    </h4>


    <div class="d-flex justify-content-between toolbar pb-0 pl-2 pr-2">
        <div>
            <?php
            if ($add_events) {
            echo Html::button('Добавить', [
            'value' => Url::to(['create', 'id' =>  $id]),
            'title' => 'Добавить причину',
            'class' => 'showModalButton btn btn-shadow btn-success']);
            }
            ?>

        <?= Html::a('Очистить фильтр',  ['clear-filter', 'id' => $reestr_key], ['class' => 'btn btn-shadow btn-danger']) ?>
        <?= Html::a('Закрыть', ['/elk/reestr'], ['class' => 'btn btn-shadow btn-secondary']) ?>

        </div>

        <div>
            <button type="button" class="btn dropdown-toggle btn-default" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                <span class="caret" id="record">...</span>
            </button>
            <ul class="dropdown-menu exp-btn" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 38px, 0px);">
                <li><a class="dropdown-item" href="/frontend/web/elk/discrepancy/index?per-page=20">20</a></li>
                <li><a class="dropdown-item" href="/frontend/web/elk/discrepancy/index?per-page=50">50</a></li>
                <li><a class="dropdown-item" href="/frontend/web/elk/discrepancy/index?per-page=100">100</a></li>
            </ul>
        </div>
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

    <div class="search-form" style="display:none">
        <?=  $this->render('_search', ['model' => $searchModel]); ?>
    </div>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'discrepancy',

            [
                'attribute' => 'id',
                'format' => 'raw',
                'label' => 'Статус выполнения',
                'value' => function ($data) {
                    $model = new Discrepancy();
                    $st = '';
                    switch ($model->getStatus($data['id'])) {
                        case '1':
                            return   $st = '<span class="text-red">Не выполнено</span>'; break;
                        case '2':
                            return   $st = '<span class="text-green">Выполнено в срок</span>'; break;
                        case '3':
                            return  $st = '<span class="text-secondary">(Нет данных)</span>'; break;
                    }
                }
            ],

            [
                'class' => 'kartik\grid\ActionColumn',
                'contentOptions' => [
                    'style' => 'width:80px;  min-width:80px; max-width:80px;'
                  //  'style' => 'white-space: normal; width: 3%; min-width: 3%; max-width: 3%;'
                ],

                'buttons' => [
                    'all' => function ($model,$key,$id) {
                        return '<div class="btn-group">' . ButtonDropdown::widget([
                            'label' => '...',
                            'options' => ['class' => 'btn-action btn-shadow btn-default'],
                            'dropdown' => [
                                'options' => ['class' => 'dropdown-menu'],
                                'items' => [
                                    [
                                        'label' => '<i class="fa fa-pen"></i> Редактировать',
                                        'encode' => false,
                                        'url' => '#',
                                        'linkOptions' => ['value'=> Url::to(['update', 'id' => $id]), 'title' => 'Редактировать','class'=>'showModalButton'],
                                       /* 'disabled' => (Yii::$app->user->can('elk_admin')||Yii::$app->user->can('elk_user_gxk')) ? false : true,*/
                                    ],
                                    [
                                        'label' => '<i class="fa fa-table"></i> Мероприятия',
                                        'encode' => false,
                                        'url' => ['/elk/events', 'id' => $id],
                                       /* 'disabled' => (Yii::$app->user->can('elk_admin')||Yii::$app->user->can('elk_user_gxk')) ? false : true,*/
                                    ],
                                    [
                                        'label' => '<i class="fa fa-trash-alt"></i> Удалить',
                                        'encode' => false,
                                        'url' => ['delete', 'id' => $id],
                                        'linkOptions' => [
                                            'data' => [
                                                'confirm' => Yii::t('app', 'Вы уверены, что хотите удалить эту запись?'),
                                                'method' => 'post',
                                            ],
                                        ],
                                        /*'disabled' => (Yii::$app->user->can('elk_admin')||Yii::$app->user->can('elk_user_gxk')) ? false : true,*/
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



