<?php
namespace frontend\modules\elk\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

use yii\helpers\Url;
use yii\helpers\BaseFileHelper;
use yii\helpers\StringHelper;
use yii\web\HttpException;
use yii\web\UploadedFile;

class BasesemController extends Controller
{

    /**
     * {@inheritdoc}
     */

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    protected function findModel($modelClass, $id)
    {
        $model = $modelClass::findOne($id);
        if (!$model) {
            throw new NotFoundHttpException(Yii::t('app', 'Запись не найдена'));
        }
        return $model;
    }



    public function actionClearFilter($modelClassName)
    {
        $session = Yii::$app->session;
        if ($session->has(StringHelper::basename($modelClassName).'Search')) {
            $session->remove(StringHelper::basename($modelClassName).'Search');
        }
        if ($session->has(StringHelper::basename($modelClassName).'SearchSort')) {
            $session->remove(StringHelper::basename($modelClassName).'SearchSort');
        }

        return $this->redirect(['index']);
    }

    //    public function actionClearFilter()
//    {
//        $session = Yii::$app->session;
//        if ($session->has('AuditorsSearch')) {
//            $session->remove('AuditorsSearch');
//        }
//
//        return $this->redirect('index');
//    }

    public function setCurrentUrl($modelClassName)
    {
        $session = Yii::$app->session;
        $session->set(StringHelper::basename($modelClassName).'Referrer', Yii::$app->request->referrer);
    }

    public function getCurrentUrl($modelClassName)
    {
        $session = Yii::$app->session;
        if ($session[StringHelper::basename($modelClassName).'Referrer'])
            return $session[StringHelper::basename($modelClassName).'Referrer'];
        return 'index';
    }




}