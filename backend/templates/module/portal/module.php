<?php
/**
 * This is the template for generating a module class file.
 */

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\module\Generator */

$className = $generator->moduleClass;
$pos = strrpos($className, '\\');
$ns = ltrim(substr($className, 0, $pos), '\\');
$className = substr($className, $pos + 1);

echo "<?php\n";
?>

namespace <?= $ns ?>;

use Yii;
use yii\helpers\Url;

/**
 * <?= $generator->moduleID ?> module definition class
 */
class <?= $className ?> extends \common\modules\main\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = '<?= $generator->getControllerNamespace() ?>';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
        Yii::$app->name = Yii::t('app', '<?= $generator->moduleID ?>');
    }

    /**
    * Get menu on the left as an array
    *
    * Override this method in your module will allow you to use the config of your module
    * @return null
    */
    public function getMenu()
    {
        $isAdmin = Yii::$app->user->can('admin');

        $items =
            [
                'items' => [
                    ['label' => Yii::t('app', 'Category 1'), 'url' => ['/<?= $generator->moduleID ?>/default'], 'icon' => 'star', 'visible' => $isAdmin],
                    [
                        'label' => Yii::t('app', 'Category 2'),
                        'items' => [
                            ['label' => Yii::t('equ', 'Category 2.1'), 'url' => ['/<?= $generator->moduleID ?>/_default']],
                            ['label' => Yii::t('equ', 'Category 2.2'), 'url' => ['/<?= $generator->moduleID ?>/_default']],
                            ['label' => Yii::t('equ', 'Category 2.3'), 'url' => ['/<?= $generator->moduleID ?>/_default']],
                            ['label' => Yii::t('equ', 'Category 2.4'), 'url' => ['/<?= $generator->moduleID ?>/_default']],
                        ],
                        'url' => ['/<?= $generator->moduleID ?>/_default'],
                        'visible' => true
                    ],
                ],
                'options' => ['class' => 'nav-pills'], // set this to nav-tab to get tab-styled navigation
                ];

            return $items;
    }

    /**
    * Get module name
    *
    * Override this method in your module will allow you to use the config of your module
    * @return string
    */
    public function getName()
    {
        return Yii::t('app', '<?= $generator->moduleID ?>');
    }

    /**
    * Get logo url
    *
    * Override this method in your module will allow you to use the config of your module
    * @return string
    */
    public function getLogoUrl()
    {
         return '/images/<?= $generator->moduleID ?>.ico';

    }

    /**
    * Get notifications menu on the top as an array
    *
    * Override this method in your module will allow you to use the config of your module
    * @return null
    */
    public function getNotificationsMenu()
    {
        $cntEvent = 7;

        $items = [
            'events' => [
                'cnt' => $cntEvent,
                'icon' => 'fa-lightbulb',
                'name' => Yii::t('app', 'New events'),
                'url' => ['/<?= $generator->moduleID ?>/default/index'],
                'class' => 'label bg-yellow',
            ],
        ];

        return $items;
    }

    public function getHomeUrl()
    {
        return Url::toRoute(['/<?= $generator->moduleID ?>/default']);
    }
}
