<?php

namespace backend\modules\administrator;
use Yii;


/**
 * administrator module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'backend\modules\administrator\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }

    public function getName () {
        return Yii::t('app', 'Module name [administrator]');
    }
}
