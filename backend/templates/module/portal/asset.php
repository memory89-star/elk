<?php
/**
 * This is the template for generating a asset class within a module.
 */

/* @var $this yii\web\View */
/* @var $generator \backend\templates\module\Generator */

$className = \yii\helpers\Inflector::id2camel($generator->moduleID);

echo "<?php\n";
?>

namespace <?= $generator->getAssetsNamespace() ?>;

use yii\web\AssetBundle;

class <?= $className ?>Asset extends AssetBundle
{
    public $sourcePath = '@frontend/modules/<?= $generator->moduleID ?>/assets';

    public $css = [
        'css/<?= $generator->moduleID ?>.css'
    ];

    public $js = [
        'js/<?= $generator->moduleID ?>.js'
    ];

    public $depends = [];

    public $publishOptions = [
        'forceCopy' => YII_ENV_DEV
    ];
}
