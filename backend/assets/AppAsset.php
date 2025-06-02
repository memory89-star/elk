<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $sourcePath = '@backend/assets';

    //public $basePath = '@webroot';
    //public $baseUrl = '@web';
    public $css = [
        'css/adminlte.css',
        'css/app-theme.css',
    ];
    public $js = [
        'js/script.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap4\BootstrapAsset',
        'yii\bootstrap4\BootstrapPluginAsset',
        'dmstr\adminlte\web\AdminLteAsset',
        'frontend\assets\FontAsset',
        'frontend\assets\AppAsset',
    ];
    public $publishOptions = [
        'forceCopy' => YII_ENV_DEV
    ];
}
