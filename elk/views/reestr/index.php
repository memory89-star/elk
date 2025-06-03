<?php

use frontend\modules\elk\models\Reestr;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use yii\bootstrap4\ButtonDropdown;
use yii\bootstrap4\Modal;
use frontend\modules\elk\models\helpers\AuthHelper;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

$this->title = 'Реестр Летучего контроля';
$this->params['breadcrumbs'][] = $this->title;

//выводим поиск

$search = "$('.search-button').click(function(){
	$('.search-form').toggle(1000);
	return false;
});";
$this->registerJs($search);


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

$model = new Reestr;


?>

<?php
//$role = Yii::$app->user->can('aud_admin');


//dump(($model->userDepartmentIdK(Yii::$app->user->identity->id)==false)?'qwe':'no');

//роли на дейтвия ***************************************************************************************
$add_events = (Yii::$app->user->can('elk_admin')||(Yii::$app->user->can('elk_user_dep')&&$model->userDepartmentIdK(Yii::$app->user->identity->id))) ? false : true;
//$alt_korr = (Yii::$app->user->can('elk_user_dep')&&$model->userDepartmentKm(Yii::$app->user->identity->id)) ? false : true;
//$view_korr =
//$korr = $model->userDepartmentKm($id);
//$alt_korr = Yii::$app->user->can('elk_admin')||Yii::$app->user->can('elk_user_gxk')||(Yii::$app->user->can('elk_user_dep')&&$model->userDepartmentKm($korr));
//********************************************************************************************************
?>
<!--if (Yii::$app->user->can('elk_admin') || Yii::$app->user->can('elk_user_gxk') && $model->userDepartmentId(Yii::$app->user->identity->id) ? true : false) {-->

    <div class="reestr-index">

        <div class="d-flex justify-content-between toolbar pb-0 pl-2 pr-2">
            <div>
        <?php
        if (Yii::$app->user->can('elk_admin')||Yii::$app->user->can('elk_user_gxk')||Yii::$app->user->can('elk_user_dep')) {
        echo Html::button('Добавить несоответствие', [
            'value' => Url::to(['create']),
            'title' => 'Добавить Несоответствие/наблюдение',
            'class' => 'showModalButton btn btn-shadow btn-success']);
            }
        ?>

        <?= Html::a('Очистить фильтр',  ['clear-filter','modelClassName' => frontend\modules\elk\models\Reestr::className()], ['class' => 'btn btn-shadow btn-danger']) ?>

           </div>

        <div>
            <button type="button" class="btn dropdown-toggle btn-default" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                <span class="caret" id="record">...</span>
            </button>
            <ul class="dropdown-menu exp-btn" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 38px, 0px);">
                <li><a class="dropdown-item" href="/frontend/web/elk/reestr/index?per-page=20">20</a></li>
                <li><a class="dropdown-item" href="/frontend/web/elk/reestr/index?per-page=50">50</a></li>
                <li><a class="dropdown-item" href="/frontend/web/elk/reestr/index?per-page=100">100</a></li>
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

<!--    <div class="search-form" style="display:none">-->
<!--        --><?//=  $this->render('_search', ['model' => $searchModel]); ?>
<!--    </div>-->


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'identification_document_number',
            [
                'attribute' => 'date_detection',
                'format' => 'raw',
                'value' => function ($data) {
                    return Yii::$app->formatter->asDate($data->date_detection, 'dd.MM.yyyy');
                }
            ],


            [
                'attribute' => 'id_department_kontrolling',
                'label' => 'Контролирующее подразделение',
                'format' => 'raw',
                'contentOptions' => [
                    'class' => 'column-main-grid__10'
                ],
                'filter' => $department_kontrolling,
                'value' => function($data) {
                    $model = new Reestr();
                    if (empty($data->id_department_kontrolling)) {
                        return "<span class='text-secondary'><i>(Нет данных)</i></span>";
                    } else {
                        $result = $model->getDepartmentById($data->id_department_kontrolling);
                        return Html::a($result[$data->id_department_kontrolling], '#', ['value'=> Url::to(['view', 'id' => $data['id']]), 'title' => 'Просмотреть', 'class' => 'showModalButton']);
                    }
                },
            ],

            [
                'attribute' => 'id_department_kontrolled',
                'label' => 'Контролируемое подразделение',
                'format' => 'raw',
                'contentOptions' => [
                    'class' => 'column-main-grid__10'
                ],
                'filter' => $department_kontrolled,
                'value' => function($data) {
                    $model = new Reestr();
                    if (empty($data->id_department_kontrolled)) {
                        return "<span class='text-secondary'><i>(Нет данных)</i></span>";
                    } else {
                        $result = $model->getDepartmentById($data->id_department_kontrolled);
                      /*  return $result[$data->id_department_kontrolled];*/
                        return Html::a($result[$data->id_department_kontrolled], '#', ['value'=> Url::to(['view', 'id' => $data['id']]), 'title' => 'Просмотреть', 'class' => 'showModalButton']);
                    }
                },
            ],

            [
                'attribute' => 'incongruity',
                'format' => 'raw',
                'value' => function ($data) {
                    return Html::a($data->incongruity, '#', [
                        'value' => Url::to(['view', 'id' => $data['id']]),
                        'title' => Yii::t('app', 'Просмотр'),
                        'class' => 'showModalButton',
                    ]);
                },
            ],

            [
                'attribute' => 'id_objects',
                'label' => 'Код объекта',
                'format' => 'raw',
                'contentOptions' => [
                    'class' => 'column-main-grid__10'
                ],
                'filter' => $id_objects,
                'value' => function($data) {
                    $model = new Reestr();
                    if (empty($data->id_objects)) {
                        return "<span class='text-secondary'><i>(Нет данных)</i></span>";
                    } else {
                        $result = $model->getKodOb($data->id_objects);
                        return $result[$data->id_objects];
                    }
                },
            ],

            [
                'attribute' => 'id_significance',
                'label' => 'Значимость',
                'format' => 'raw',
                'contentOptions' => [
                    'class' => 'column-main-grid__10'
                ],
                'filter' => $id_significance,
                'value' => function($data) {
                    $model = new Reestr();
                    if (empty($data->id_significance)) {
                        return "<span class='text-secondary'><i>(Нет данных)</i></span>";
                    } else {
                        $result = $model->getKodZn($data->id_significance);
                        return $result[$data->id_significance];
                    }
                },
            ],

          /*  'date_plan',*/
            [
                'attribute' => 'date_plan',
                'format' => 'raw',
                'value' => function ($data) {
                    return Yii::$app->formatter->asDate($data->date_plan);
                }
            ],

          'year',
            [
                'attribute' => 'month',
                'format' => 'raw',
                'filter' => $month,
                'value' => function($data) {
                    switch($data->month) {
                        case '01':
                            return "<span>Январь</span>";
                            break;
                        case '02':
                            return "<span>Февраль</span>";
                            break;
                        case '03':
                            return "<span>Март</span>";
                            break;
                        case '04':
                            return "<span>Апрель</span>";
                            break;
                        case '05':
                            return "<span>Май</span>";
                            break;
                        case '06':
                            return "<span>Июнь</span>";
                            break;
                        case '07':
                            return "<span>Июль</span>";
                            break;
                        case '08':
                            return "<span>Август</span>";
                            break;
                        case '09':
                            return "<span>Сентябрь</span>";
                            break;
                        case '10':
                            return "<span>Октябрь</span>";
                            break;
                        case '11':
                            return "<span>Ноябрь</span>";
                            break;
                        case '12':
                            return "<span>Декабрь</span>";
                            break;
                        default:
                            return "<span class='text-secondary'><i>(Нет данных)</i></span>";
                            break;
                    }
                },
            ],

            [
                'attribute' => 'date_fact',
                'format' => 'raw',
                'value' => function ($data) {
                    return Yii::$app->formatter->asDate($data->date_fact);
                }
            ],

            [
                'attribute' => 'id',
                'format' => 'raw',
                'label' => 'Статус выполнения',
                'value' => function ($data) {
                    $model = new Reestr();
                    $st = '';
                    switch ($model->getStepStatus($data['id'])) {
                        case '1':
                            return   $st = '<span class="text-dark">В работе</span>'; break;
                        case '2':
                            return   $st = '<span class="text-red">Не выполнено</span>'; break;
                        case '3':
                            return   $st = '<span class="text-orange">Просрочено</span>'; break;
                        case '4':
                            return  $st = '<span class="text-green">Выполнено в срок</span>'; break;
                    }
                }
            ],




            [
                'class' => 'kartik\grid\ActionColumn',
                'contentOptions' => [
                    'style' => 'column-main-grid__10'
                ],
                'buttons' => [
                    'all' => function ($model,$key,$id) use($add_events) {
                        return '<div class="btn-group">' . ButtonDropdown::widget([
                                'label' => '...',
                                'options' => ['class' => 'btn-action btn-shadow btn-default'],
                                'dropdown' => [
                                    'options' => ['class' => 'dropdown-menu my_width_ddm'],
                                    'items' => [
                                    [
                                        'label' => '<i class="fa fa-pen"></i> Редактировать Коррекции',
                                        'encode' => false,
                                        'url' => '#',
                                        'linkOptions' => ['value'=> Url::to(['update', 'id' => $id]),'title' => 'Редактировать Коррекции','class'=>'showModalButton'],
                                      /*  var_dump($model->userDepartmentKm($model->id_department_kontrolling)),*/
                                    ],

                                         [
                                        'label' => '<i class="fa fa-pen"></i> Добавить мероприятия',
                                        'encode' => false,
                                        'url' => '#',
                                        'linkOptions' => ['value'=> Url::to(['updateevents', 'id' => $id]), 'title' => 'Добавить Мероприятия по несоответствию блока "Коррекции"','class'=>'showModalButton'],
                                        'disabled' => $add_events,

                                    ],

                                 [
                                     'label' => '<i class="fa fa-eye"></i> Просмотреть Коррекции',
                                     'encode' => false,
                                     'url' => '#',
                                     'linkOptions' => [
                                         'value' => Url::to(['view', 'id' => $id]),
                                         'title' => 'Просмотреть Коррекции',
                                         'class' => 'showModalButton',
                                     ],

                                 ],

                                [
                                    'label' => '<i class="fa fa-table"></i> Причины несоответствия',
                                    'encode' => false,
                                    'url' => ['/elk/discrepancy', 'id' => $id],
                                    /*'disabled' => Yii::$app->user->can('elk_admin')||Yii::$app->user->can('elk_user_gxk') ? false : true,*/
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



