<?php

use common\models\search\UserSearch;
use frontend\modules\employee\models\Department;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $mainDepartments array */
/* @var $authItems array */

$this->title = Yii::t('app', 'Пользователи');
$this->params['breadcrumbs'][] = $this->title;

$columns = [
    'id',
    [
        'attribute' => 'username',
        'format' => 'raw',
        'value' => function ($data) {
            return Html::a($data['username'], Url::toRoute(['/admin/assignment/view', 'id' => $data['id']]));
        }
    ],
    'email:email',
    /*[
        'attribute' => 'main_department_id',
        'value' => function ($data) {
            return $data['main_department_id'] ? Department::findOne($data['main_department_id'])->getFullName() : null;
        },
        'filter' => $mainDepartments
    ],*/
    'stabnum:raw:' . $searchModel->getAttributeLabel('stabnum'),
    'secondname',
    'firstname',
    'thirdname',
    [
        'attribute' => 'status',
        'value' => function ($model) {
            return $model['status'] == 0 ? 'Inactive' : 'Active';
        },
        'filter' => [
            0 => 'Inactive',
            10 => 'Active'
        ]
    ],
    'last_login_at:datetime:' . $searchModel->getAttributeLabel('last_login_at'),
    'created_at:datetime:' . $searchModel->getAttributeLabel('created_at'),
    'updated_at:datetime:' . $searchModel->getAttributeLabel('updated_at'),
    'blocked_at:datetime:' . $searchModel->getAttributeLabel('blocked_at'),
    [
        'class' => 'kartik\grid\ActionColumn',
        'template' => '{permissions} {update} {delete}',
        //'template' => '{permissions}',
        'buttons' => [
            'permissions' => function ($url, $model) {
                return Html::a('<span class="glyphicon glyphicon-list-alt"></span>', ['/admin/assignment/view', 'id' => $model['id']], [
                    'title' => Yii::t('yii', 'Разрешения'),
                    'data-pjax' => '0',
                ]);
            }
        ]
    ],
];
?>
<div class="user-index">

    <?= $this->render('_search.php', [
        'searchModel' => $searchModel,
        'mainDepartments' => $mainDepartments,
        'authItems' => $authItems,
    ]) ?>

    <?php echo $toolbar = \common\widgets\GridToolbar::widget([
        'gridColumns' => $columns,
        'dataProvider' => $dataProvider,
        'content' => Html::a(Yii::t('app', 'Register User'), ['create'], ['class' => 'btn btn-info'])
    ]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => $columns,
    ]); ?>
</div>
