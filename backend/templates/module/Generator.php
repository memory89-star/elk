<?php

namespace backend\templates\module;

use yii\gii\CodeFile;
use yii\helpers\BaseFileHelper;
use yii\helpers\Html;
use Yii;
use yii\helpers\StringHelper;

class Generator extends \yii\gii\generators\module\Generator
{
    /**
     * {@inheritdoc}
     */
    public function requiredTemplates()
    {
        return ['module.php', 'controller.php', 'view.php', 'asset.php', 'style.css', 'script.js'];
    }

    /**
     * {@inheritdoc}
     */
    public function generate()
    {
        $className = \yii\helpers\Inflector::id2camel($this->moduleID);
        $files = [];
        $modulePath = $this->getModulePath();
        $files[] = new CodeFile(
            $modulePath . '/' . StringHelper::basename($this->moduleClass) . '.php',
            $this->render("module.php")
        );
        $files[] = new CodeFile(
            $modulePath . '/controllers/DefaultController.php',
            $this->render("controller.php")
        );
        $files[] = new CodeFile(
            $modulePath . '/views/default/index.php',
            $this->render("view.php")
        );
        $files[] = new CodeFile(
            $modulePath . '/assets/'. $className .'Asset.php',
            $this->render("asset.php")
        );
        $files[] = new CodeFile(
            $modulePath . '/assets/css/'.$this->moduleID.'.css',
            $this->render("style.css")
        );
        $files[] = new CodeFile(
            $modulePath . '/assets/js/'.$this->moduleID.'.js',
            $this->render("script.js")
        );

        BaseFileHelper::createDirectory($modulePath . '/' . '/migrations');

        return $files;
    }

    /**
     * @return string the controller namespace of the module.
     */
    public function getAssetsNamespace()
    {
        return substr($this->moduleClass, 0, strrpos($this->moduleClass, '\\')) . '\assets';
    }
}
