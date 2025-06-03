<?php

namespace frontend\modules\elk;

use frontend\modules\elk\models\File;
use Yii;
use yii\helpers\Url;

/**
 * risk module definition class
 */
class Module extends \common\modules\main\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'frontend\modules\elk\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
        Yii::$app->name = Yii::t('app', 'elk');
    }

    /**
     * Get menu on the left as an array
     *
     * Override this method in your module will allow you to use the config of your module
     * @return null
     */
    public function getMenu()
    {
        $isElkAdmin = Yii::$app->user->can('elk_admin');
        $items =
            [
                'items' => [
                    ['label' => Yii::t('app', 'Список ЛК'), 'url' => ['/elk/reestr/index'], 'icon' => 'cloud'],
                    [
                        'label' => Yii::t('app', 'СПРАВОЧНИКИ ЭЛК'),
                        'items' => [
                            ['label' => Yii::t('app', 'Подразделения ЭЛК'), 'url' => ['/elk/department-data/index'], 'icon' => 'minus'],
                            ['label' => Yii::t('app', 'Коды объектов ЛК'), 'url' => ['/elk/kod'], 'icon' => 'minus'],
                            ['label' => Yii::t('app', 'Значимость несоответствия'), 'url' => ['/elk/significance'], 'icon' => 'minus'],
                            ['label' => Yii::t('app', 'Этапы реализации'), 'url' => ['/elk/step'], 'icon' => 'minus'],
                        ],
                        'visible' => true,
                        'icon' => 'book'
                    ],
                    [
                        'label' => Yii::t('app', 'ДОКУМЕНТАЦИЯ'),
                        'items' => [
                            ['label' => Yii::t('app', 'Руководство пользователя'), 'url' => ['/elk/file', 'pathSubDir' => File::getStaticPathUserGuide()], 'icon' => 'minus'],
                            ['label' => Yii::t('app', 'Доп. информация'), 'url' => ['/elk/file', 'pathSubDir' => File::getStaticPathPosters()], 'icon' => 'minus'],
                        ],
                        'visible' => true,
                        'icon' => 'file'
                    ],
                    [
                        'label' => Yii::t('app', 'ОТЧЕТЫ'),
                        'items' => [
                            ['label' => Yii::t('app', 'Печать Реестра ЭЛК'), 'url' => ['reestr-print/index'], 'icon' => 'minus'],
                        ],
                        'visible' => true,
                        'icon' => 'print'
                    ],
                ],
                'options' => ['class' => 'nav-pills'],
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
        return Yii::t('app', 'Электронный ЛК');
    }

    /**
     * Get logo url
     *
     * Override this method in your module will allow you to use the config of your module
     * @return string
     */
    public function getLogoUrl()
    {
//        return '/images/risk.png';
    }

    /**
     * Get home url
     *
     * Override this method in your module will allow you to use the config of your module
     * @return string
     */
    public function getHomeUrl()
    {
        return Url::toRoute(['/elk/reestr/index']);
    }
}
