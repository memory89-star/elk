<?php

use mdm\admin\AnimateAsset;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\YiiAsset;
use \backend\assets\AppAsset;

/* @var $this yii\web\View */
/* @var $model mdm\admin\models\Assignment */
/* @var $fullnameField string */

$baseFrontendUrl = Yii::$app->urlManagerFrontEnd->baseUrl;

$script = <<< SCRIPT
    var list = $('select.list[data-target="available"]');
    
    var s = $('select').mouseover(function (e) {
        var name = e.target.value;
        
        if(name)
        {
            $.ajax({
                url: '$baseFrontendUrl' + '/employee/assignment/get-role?roleName=' + name,
                type: 'get',
                dataType: 'json',
                success: function (data) {
                    $('#popover').text(data.description);
                },
                delay: 2600
            });
        }
    }); 
SCRIPT;
$this->registerJs($script);

$userName = $model->{$usernameField};
if (!empty($fullnameField)) {
    $userName .= ' (' . ArrayHelper::getValue($model, $fullnameField) . ')';
}
$userName = Html::encode($userName);

$this->title = Yii::t('rbac-admin', 'Assignment') . ' : ' . $userName;

$this->params['breadcrumbs'][] = ['label' => Yii::t('rbac-admin', 'Assignments'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $userName;

AnimateAsset::register($this);
YiiAsset::register($this);

$opts = Json::htmlEncode([
    'items' => $model->getItems(),
]);
$this->registerJs("var _opts = {$opts};");
$this->registerJs($this->render('_script.js'));
$animateIcon = ' <i class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></i>';
?>
<div class="assignment-index">
    <h1><?= $this->title; ?></h1>

    <div class="row">
        <div class="col-sm-5">
            <input class="form-control search" data-target="available"
                   placeholder="<?= Yii::t('rbac-admin', 'Search for available'); ?>">
            <select multiple size="30" class="form-control list" data-target="available">
            </select>
        </div>
        <div class="col-sm-1">
            <br><br>
            <?= Html::a('&gt;&gt;' . $animateIcon, ['assign', 'id' => (string)$model->id], [
                'class' => 'btn btn-success btn-assign',
                'data-target' => 'available',
                'title' => Yii::t('rbac-admin', 'Assign'),
            ]); ?><br><br>
            <?= Html::a('&lt;&lt;' . $animateIcon, ['revoke', 'id' => (string)$model->id], [
                'class' => 'btn btn-danger btn-assign',
                'data-target' => 'assigned',
                'title' => Yii::t('rbac-admin', 'Remove'),
            ]); ?>
        </div>
        <div class="col-sm-5">
            <input class="form-control search" data-target="assigned"
                   placeholder="<?= Yii::t('rbac-admin', 'Search for assigned'); ?>">
            <select multiple size="30" class="form-control list" data-target="assigned">
            </select>
        </div>
    </div>

    <h4 id="popover"></h4>
</div>
