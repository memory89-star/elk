<?php

namespace frontend\modules\elk\controllers;

use common\modules\userProfile\models\UserProfile;
use frontend\modules\elk\models\Discrepancy;
use frontend\modules\elk\models\helpers\AuthHelper;
use frontend\modules\elk\models\Reestr;
use frontend\modules\elk\models\Kod;
use Yii;
use frontend\modules\elk\models\search\DiscrepancySearch;
use yii\base\BaseObject;
use yii\bootstrap4\ActiveForm;
use kartik\mpdf\Pdf;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\web\Response;


class DiscrepancyController extends BasesemController
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
    public function actionIndex($id)
    {
        $searchModel = new DiscrepancySearch();
        $searchModel->search([$searchModel->formName()=>['id_reestr' => $id]]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $reestr = Reestr::find()->all();
        $id_reestr = ArrayHelper::map($reestr, 'id', 'incongruity');

        $reestr = Reestr::find()->all();
        $id_reestr1 = ArrayHelper::map($reestr, 'id', 'id');

        $kod = Reestr::find()->all();
        $id_kod = ArrayHelper::map($kod, 'id', 'id_objects');

        $model = new Discrepancy();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'id' => $id,
            'reestr_key' => $id,
            'id_reestr' => $id_reestr,
            'id_reestr1' => $id_reestr1,
//            'id_kod' => ,
        ]);
    }

    public function actionView($id)
    {
        $modelStep = new Discrepancy();
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

    public function actionCreate($id)
    {
        $model = new Discrepancy();
        $model->id_reestr = $id;
//        $model->user_last = Yii::$app->user->identity->username;
//        $model->user_first = Yii::$app->user->identity->username;
        $model->user_last = Yii::$app->user->identity->id;
        $model->user_first = Yii::$app->user->identity->id;

        $user_id = Yii::$app->user->identity->id;
        $profile = UserProfile::find()->select(['main_department_id'])->where(['user_id' => $user_id])->one();

        if (!AuthHelper::isReestrAdmin()) {

            if (AuthHelper::canEditDepartmentReestr()){
                if ($model->getDepartmentId($model->id_reestr) <> $profile->main_department_id) {
                    Yii::$app->session->addFlash(
                        'error',
                        Yii::t(
                            'app',
                            'Подразделение пользователя не соответствует подразделению ЭЛК или не является "Контролируемым", добавление причины несоответствия невозможно', [
//                                $profile->departmentData->getShortCodeShortName(),
//                                $model->departmentData->getShortCodeShortName()
                        ])
                    );
                    $url = $this->getCurrentUrl(Discrepancy::className());
                    return $this->redirect($url);
                }
            }
        }

        $model->created_at = Date('Y-m-d'); //('d.m.Y')
        $model->time_create = Date('H:i:s');
        $model->updated_at = Date('Y-m-d'); //('d.m.Y')
        $model->time_update = Date('H:i:s');

        $reestr = Reestr::find()->all();
        $id_reestr1 = ArrayHelper::map($reestr, 'id', 'incongruity');

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $url = $this->getCurrentUrl(Discrepancy::className());
            echo Yii::$app->session->setFlash('success', 'Запись успешно добавлена!');
            return $this->redirect($url);
        }

        $this->setCurrentUrl(Discrepancy::className());

        if (Yii::$app->request->isAjax){
            return $this->renderAjax('modalForm', [
                'model' => $model,
                'user_last' => $model->getUserById(Yii::$app->user->identity->id),
                'date_time_create' => $model->created_at.' '.$model->time_create,
                'user_first' => $model->getUserById(Yii::$app->user->identity->id),
                'date_time_update' => $model->updated_at.' '.$model->time_update,
                'id' => $id,
                'id_reestr1' => $id_reestr1,
            ]);
        } else {
            throw new NotFoundHttpException('The requested page does not exist...');
        }
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel(Discrepancy::className(), $id);
        $modelOld = $this->findModel(Discrepancy::className(), $id);

//        $model->user_last = Yii::$app->user->identity->username;
        $model->user_last = Yii::$app->user->identity->id;

        $user_id = Yii::$app->user->identity->id;
        $profile = UserProfile::find()->select(['main_department_id'])->where(['user_id' => $user_id])->one();

        if (!AuthHelper::isReestrAdmin()) {

            if (AuthHelper::canEditDepartmentReestr()){
                if ($model->getDepartmentId($model->id_reestr) <> $profile->main_department_id) {
                    Yii::$app->session->addFlash(
                        'error',
                        Yii::t(
                            'app',
                            'Подразделение пользователя не соответствует подразделению ЭЛК или не является "Контролируемым", редактирование причины несоответствия невозможно', [
//                                $profile->departmentData->getShortCodeShortName(),
//                                $model->departmentData->getShortCodeShortName()
                        ])
                    );
                    $url = $this->getCurrentUrl(Discrepancy::className());
                    return $this->redirect($url);
                }
            }
        }

        $model->created_at = Date('Y-m-d'); //('d.m.Y')
        $model->time_create = Date('H:i:s');
        $model->updated_at = Date('Y-m-d'); //('d.m.Y')
        $model->time_update = Date('H:i:s');

        $reestr = Reestr::find()->all();
        $id_reestr1 = ArrayHelper::map($reestr, 'id', 'incongruity');

//        $block = $model->block();

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $url = $this->getCurrentUrl(Discrepancy::className());
            echo Yii::$app->session->setFlash('success', 'Запись успешно обнавлена!');
            return $this->redirect($url);
        }

        $this->setCurrentUrl(Discrepancy::className());


        if (Yii::$app->request->isAjax){
            return $this->renderAjax('modalUpdate', [
                'model' => $model,
                'user_last' => $model->getUserById(Yii::$app->user->identity->id),
                'date_time_create' => $model->created_at.' '.$model->time_create,
                'user_first' => $model->getUserById(Yii::$app->user->identity->id),
                'date_time_update' => $model->updated_at.' '.$model->time_update,
                'id' => $id,
                'id_reestr1' => $id_reestr1,
            ]);
        } else {
            throw new NotFoundHttpException('The requested page does not exist...');
        }
    }

    public function actionDelete($id)
    {


        try {

            $this->setCurrentUrl(Discrepancy::className());
            $this->findModel(Discrepancy::className(), $id)->delete();

            $user_id = Yii::$app->user->identity->id;
            $profile = UserProfile::find()->select(['main_department_id'])->where(['user_id' => $user_id])->one();

            if (!AuthHelper::isReestrAdmin()) {

                if (AuthHelper::canEditDepartmentReestr()){
                    if ($this->getDepartmentId($this->id_reestr) <> $profile->main_department_id) {
                        Yii::$app->session->addFlash(
                            'error',
                            Yii::t(
                                'app',
                                'Подразделение пользователя не соответствует подразделению ЭЛК или не является "Контролируемым", удаление причины несоответствия невозможно', [
//                                $profile->departmentData->getShortCodeShortName(),
//                                $model->departmentData->getShortCodeShortName()
                            ])
                        );
                        $url = $this->getCurrentUrl(Discrepancy::className());
                        return $this->redirect($url);
                    }
                }
            }

            $url = $this->getCurrentUrl(Discrepancy::className());
            Yii::$app->session->setFlash('success', Yii::t('app', 'Запись успешно удалена!'));
        } catch (\Exception $exception) {
            Yii::$app->session->setFlash('error', Yii::t('app', 'Внимание: выбранный реестр уже используется! Удаление не возможно!'));
        }
        return $this->redirect($url);
    }

    /**
     * Clear filter.
     * @param $id number
     */
    public function actionClearFilter($id)
    {
        $session = Yii::$app->session;
        if ($session->has('DiscrepancySearch')) {
            $session->remove('DiscrepancySearch');
        }
        if ($session->has('DiscrepancySearchSort')) {
            $session->remove('DiscrepancySearchSort');
        }
        return $this->redirect(['index', 'id' => $id]);
    }

//    /**
//     * Search reestr information, in Reestr model.
//     * @return mixed
//     */
//    public function actionSearchField()
//    {
//        $model = new Reestr();
//
//        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
//        $id_department_kontrolled = $_POST['date_id'];
//        return $model->getInformationDate($id_department_kontrolled);
//    }
//
//    /**
//     * Search reestr information, in Reestr model.
//     * @return mixed
//     */
//    public function actionSearchOb()
//    {
//        $model = new Reestr();
//
//        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
//        $id_objects = $_POST['ob_id'];
//        return $model->getInformationKod($id_objects);
//    }

}