<?php

namespace frontend\modules\elk\controllers;

use Yii;
use frontend\modules\elk\models\Significance;
use frontend\modules\elk\models\search\SignificanceSearch;
use yii\base\BaseObject;
use yii\bootstrap4\ActiveForm;
use kartik\mpdf\Pdf;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\web\Response;


class SignificanceController extends BasesemController
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];

    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Lists all Step models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SignificanceSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $model = new Significance();
        $block = $model->block();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'block' => $block,
        ]);
    }

    public function actionView($id)
    {
        $modelStep = new Significance();
        $model = $this->findModel($id);
//        $model->user_last = Yii::$app->user->identity->username;
//        $model->user_first = Yii::$app->user->identity->username;
        $model->user_last = Yii::$app->user->identity->id;
        $model->user_first = Yii::$app->user->identity->id;

        if (Yii::$app->request->isAjax){
            return $this->renderAjax('modalView', [
                'model' => $model,
            ]);
        } else {
            return $this->render('modalView', [
                'model' => $model,
            ]);
        }
    }

    public function actionCreate()
    {
        $model = new Significance();
//        $model->user_last = Yii::$app->user->identity->username;
//        $model->user_first = Yii::$app->user->identity->username;
        $model->user_last = Yii::$app->user->identity->id;
        $model->user_first = Yii::$app->user->identity->id;

        $model->created_at = Date('Y-m-d'); //('d.m.Y')
        $model->time_create = Date('H:i:s');
        $model->updated_at = Date('Y-m-d'); //('d.m.Y')
        $model->time_update = Date('H:i:s');
        $model->block = '0';

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $url = $this->getCurrentUrl(Significance::className());
            echo Yii::$app->session->setFlash('success', 'Запись успешно добавлена!');
            return $this->redirect($url);
        }

        $block = $model->block();

        $this->setCurrentUrl(Significance::className());

        if (Yii::$app->request->isAjax){
            return $this->renderAjax('modalForm', [
                'model' => $model,
                'block' => $block,
                'user_last' => $model->getUserById(Yii::$app->user->identity->id),
                'date_time_create' => $model->created_at.' '.$model->time_create,
                'user_first' => $model->getUserById(Yii::$app->user->identity->id),
                'date_time_update' => $model->updated_at.' '.$model->time_update,
            ]);
        } else {
            throw new NotFoundHttpException('The requested page does not exist...');
        }
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel(Significance::className(), $id);
        $modelOld = $this->findModel(Significance::className(), $id);

//        $model->user_last = Yii::$app->user->identity->username;
        $model->user_last = Yii::$app->user->identity->id;

        $model->created_at = Date('Y-m-d'); //('d.m.Y')
        $model->time_create = Date('H:i:s');
        $model->updated_at = Date('Y-m-d'); //('d.m.Y')
        $model->time_update = Date('H:i:s');

        $block = $model->block();

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $url = $this->getCurrentUrl(Significance::className());
            echo Yii::$app->session->setFlash('success', 'Запись успешно обнавлена!');
            return $this->redirect($url);
        }

        $this->setCurrentUrl(Significance::className());


        if (Yii::$app->request->isAjax){
            return $this->renderAjax('modalForm', [
                'model' => $model,
                'block' => $block,
                'user_last' => $model->getUserById(Yii::$app->user->identity->id),
                'date_time_create' => $model->created_at.' '.$model->time_create,
                'user_first' => $model->getUserById(Yii::$app->user->identity->id),
                'date_time_update' => $model->updated_at.' '.$model->time_update,
            ]);
        } else {
            throw new NotFoundHttpException('The requested page does not exist...');
        }
    }

}