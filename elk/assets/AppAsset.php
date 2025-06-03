<?php

namespace app\modules\elk\assets;

use yii\web\AssetBundle;

class AppAsset extends AssetBundle
{
    public $sourcePath = '@frontend/modules/elk/assets';

    public $css = [
        'css/elk.css'
    ];

    public $js = [
        'js/elk.js'
    ];
    
    public $depends = [
		'yii\web\YiiAsset',
        'yii\bootstrap4\BootstrapAsset',
        'yii\bootstrap4\BootstrapPluginAsset',
    ];
    
    public $publishOptions = [
        'forceCopy' => YII_ENV_DEV
    ];
}
