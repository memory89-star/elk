<?php

namespace frontend\modules\elk\controllers;

use frontend\modules\elk\models\helpers\AuthHelper;
use frontend\modules\elk\models\DepartmentData;
use frontend\modules\elk\models\helpers\SendMailElk;
use frontend\modules\elk\models\Card;
use Yii;
use frontend\modules\elk\models\Reestr;
use frontend\modules\elk\models\search\ReestrSearch;
use frontend\modules\elk\models\Step;
use common\modules\userProfile\models\UserProfile;
use yii\base\BaseObject;
use yii\bootstrap4\ActiveForm;
use kartik\mpdf\Pdf;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\web\Response;


class ReestrController extends BasesemController
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => [
                            'logout',
                            'define-step-status',],
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
        $searchModel = new ReestrSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $model = new Reestr();

        $podr = DepartmentData::find()->all();
        $id_department_kontrolled = ArrayHelper::map($podr, 'id', 'emp_department_code');

        $month = [
            '01' => 'Январь',
            '02' => 'Февраль',
            '03' => 'Март',
            '04' => 'Апрель',
            '05' => 'Май',
            '06' => 'Июнь',
            '07' => 'Июль',
            '08' => 'Август',
            '09' => 'Сентябрь',
            '10' => 'Октябрь',
            '11' => 'Ноябрь',
            '12' => 'Декабрь',
        ];

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'id_department_kontrolled' => $id_department_kontrolled,
            'department_kontrolling' => $model->getDepartment(),
            'department_kontrolled' => $model->getDepartment(),
            'id_objects' => $model->getKodObALL(),
            'id_significance' => $model->getKodZnALL(),
            'month' => $month,
//            'status' => $model->getStatus(),
        ]);
    }

    /**
     * View Reestr model by id.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {

        $model = $this->findModel1($id);

//        $model->user_last = Yii::$app->user->identity->username;
//        $model->user_first = Yii::$app->user->identity->username;

        $model->user_last = Yii::$app->user->identity->id;
        $model->user_first = Yii::$app->user->identity->id;

        //        $userr = Yii::$app->user;
        $user_id = Yii::$app->user->identity->id;
        $profile = UserProfile::find()->select(['main_department_id'])->where(['user_id' => $user_id])->one();
//        $profile = $user_id->userProfile;


        if (!AuthHelper::isReestrAdmin()) {

            if (AuthHelper::canEditDepartmentReestr()){
                if ($model->getDepartmentId($model->id_department_kontrolling) <> $profile->main_department_id && $model->getDepartmentId($model->id_department_kontrolled) <> $profile->main_department_id) {
                    Yii::$app->session->addFlash(
                        'error',
                        Yii::t(
                            'app',
                            'Подразделение пользователя не соответствует подразделению ЭЛК, просмотр невозможен', [
//                                $profile->departmentData->getShortCodeShortName(),
//                                $model->departmentData->getShortCodeShortName()
                        ])
                    );
                    $url = $this->getCurrentUrl(Reestr::className());
                    return $this->redirect($url);
                }
            }
        }

        $model->created_at = Date('Y-m-d'); //('d.m.Y')
        $model->time_create = Date('H:i:s');
        $model->updated_at = Date('Y-m-d'); //('d.m.Y')
        $model->time_update = Date('H:i:s');

        if (Yii::$app->request->isAjax){
            return $this->renderAjax('modalView', [
                'model' => $model,
                'user' => $model->getUser(),
                'user_last' => $model->getUserById(Yii::$app->user->identity->id),
                'date_time_create' => $model->created_at.' '.$model->time_create,
                'user_first' => $model->getUserById(Yii::$app->user->identity->id),
                'date_time_update' => $model->updated_at.' '.$model->time_update,
            ]);
        } else {
            throw new NotFoundHttpException('The requested page does not exist...');
        }
    }

    public function actionCreate()
    {
        $model = new Reestr();
//        $model->user_last = Yii::$app->user->identity->username;
//        $model->user_first = Yii::$app->user->identity->username;
        $model->user_last = Yii::$app->user->identity->id;
        $model->user_first = Yii::$app->user->identity->id;
        $model->id_step = $model->getStepByStatus('v_for_creating_doc');

        $model->created_at = Date('Y-m-d'); //('d.m.Y')
        $model->time_create = Date('H:i:s');
        $model->updated_at = Date('Y-m-d'); //('d.m.Y')
        $model->time_update = Date('H:i:s');
        $model->date_registr = Date('d.m.Y'); //('d.m.Y')

        $month = [
            '01' => 'Январь',
            '02' => 'Февраль',
            '03' => 'Март',
            '04' => 'Апрель',
            '05' => 'Май',
            '06' => 'Июнь',
            '07' => 'Июль',
            '08' => 'Август',
            '09' => 'Сентябрь',
            '10' => 'Октябрь',
            '11' => 'Ноябрь',
            '12' => 'Декабрь',
        ];

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $url = $this->getCurrentUrl(Reestr::className());

            SendMailElk::newElkMail($model,SendMailElk::MAIL_NEW_ELK);


            echo Yii::$app->session->setFlash('success', 'Запись успешно добавлена!');
            return $this->redirect($url);
        }

        $this->setCurrentUrl(Reestr::className());

        if (Yii::$app->request->isAjax){
            return $this->renderAjax('modalForm', [
                'model' => $model,
                'id_department_kontrolled' => $model->getDepartmentListKontr(),
                'id_department_kontrolling' => $model->getDepartmentListKontrl(),
                'id_objects' => $model->getKodOb(),
                'id_significance' => $model->getKodZn(),
                'date_registr' => $model->date_registr,
//                'user' => $model->getUser(),
                'step' => $model->getStep(),
                'cards' => Card::getEmployeesList(),
//                'user_last' => $model->getUserById(),
                'user_last' => $model->getUserById(Yii::$app->user->identity->id),
                'date_time_create' => $model->created_at.' '.$model->time_create,
                'user_first' => $model->getUserById(Yii::$app->user->identity->id),
                'date_time_update' => $model->updated_at.' '.$model->time_update,
                'month' => $month,
//                'step' => $model->getStep(),
//                'status' => $model->getStatus(),
            ]);
        } else {
            throw new NotFoundHttpException('The requested page does not exist...');
        }
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel(Reestr::className(), $id);
        $modelOld = $this->findModel(Reestr::className(), $id);

//        $model->user_last = Yii::$app->user->identity->username;
        $model->user_last = Yii::$app->user->identity->id;

//        $userr = Yii::$app->user;
        $user_id = Yii::$app->user->identity->id;
        $profile = UserProfile::find()->select(['main_department_id'])->where(['user_id' => $user_id])->one();
//        $profile = $user_id->userProfile;


        if (!AuthHelper::isReestrAdmin()) {

            if (AuthHelper::canEditDepartmentReestr()){
                if ($model->getDepartmentId($model->id_department_kontrolling) <> $profile->main_department_id && $model->getDepartmentId($model->id_department_kontrolled) <> $profile->main_department_id) {
                    Yii::$app->session->addFlash(
                        'error',
                        Yii::t(
                            'app',
                            'Подразделение пользователя не соответствует подразделению ЭЛК, редактирование невозможно', [
//                                $profile->departmentData->getShortCodeShortName(),
//                                $model->departmentData->getShortCodeShortName()
                            ])
                    );
                    $url = $this->getCurrentUrl(Reestr::className());
                    return $this->redirect($url);
                }
            }
        }

        $model->created_at = Date('Y-m-d'); //('d.m.Y')
        $model->time_create = Date('H:i:s');
        $model->updated_at = Date('Y-m-d'); //('d.m.Y')
        $model->time_update = Date('H:i:s');

        $month = [
            '01' => 'Январь',
            '02' => 'Февраль',
            '03' => 'Март',
            '04' => 'Апрель',
            '05' => 'Май',
            '06' => 'Июнь',
            '07' => 'Июль',
            '08' => 'Август',
            '09' => 'Сентябрь',
            '10' => 'Октябрь',
            '11' => 'Ноябрь',
            '12' => 'Декабрь',
        ];

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $url = $this->getCurrentUrl(Reestr::className());
            echo Yii::$app->session->setFlash('success', 'Запись успешно обнавлена!');

//            if ($model->date_fact != NULL) {
//                SendMailElk::newElkMail($model,SendMailElk::MAIL_NEW_CLOSE);
//            }

            return $this->redirect($url);
        }

        $this->setCurrentUrl(Reestr::className());


        if (Yii::$app->request->isAjax){
            return $this->renderAjax('modalUpdate', [
                'model' => $model,
                'id_department_kontrolled' => $model->getDepartmentListKontr(),
                'id_department_kontrolling' => $model->getDepartmentListKontrl(),
                'id_objects' => $model->getKodOb(),
                'id_significance' => $model->getKodZn(),
                'id_step' => $model->getStep(),
                'user' => $model->getUser(),
//                'id_otvetst' => $model->getUser(),
//                'id_kontrol' => $model->getUser(),
                'cards' => Card::getEmployeesList(),
                'date_registr' => $model->date_registr,
                'user_last' => $model->getUserById(Yii::$app->user->identity->id),
                'date_time_create' => $model->created_at.' '.$model->time_create,
                'user_first' => $model->getUserById(Yii::$app->user->identity->id),
                'date_time_update' => $model->updated_at.' '.$model->time_update,
                'month' => $month,
            ]);
        } else {
            throw new NotFoundHttpException('The requested page does not exist...');
        }
    }

    public function actionUpdateevents($id)
    {
        $model = $this->findModel(Reestr::className(), $id);
        $modelOld = $this->findModel(Reestr::className(), $id);

        $model->user_last = Yii::$app->user->identity->id;
        $model->id_step = $model->getStepByStatus('v_after_accept_event');

        //        $userr = Yii::$app->user;
        $user_id = Yii::$app->user->identity->id;
        $profile = UserProfile::find()->select(['main_department_id'])->where(['user_id' => $user_id])->one();
//        $profile = $user_id->userProfile;

        if (!AuthHelper::isReestrAdmin()) {

            if (AuthHelper::canEditDepartmentReestr()){
                if ($model->getDepartmentId($model->id_department_kontrolling) <> $profile->main_department_id && $model->getDepartmentId($model->id_department_kontrolled) <> $profile->main_department_id) {
                    Yii::$app->session->addFlash(
                        'error',
                        Yii::t(
                            'app',
                            'Подразделение пользователя не соответствует подразделению ЭЛК, добавление мероприятия невозможно', [
//                                $profile->departmentData->getShortCodeShortName(),
//                                $model->departmentData->getShortCodeShortName()
                        ])
                    );
                    $url = $this->getCurrentUrl(Reestr::className());
                    return $this->redirect($url);
                }
            }
        }

        $model->created_at = Date('Y-m-d'); //('d.m.Y')
        $model->time_create = Date('H:i:s');
        $model->updated_at = Date('Y-m-d'); //('d.m.Y')
        $model->time_update = Date('H:i:s');

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $url = $this->getCurrentUrl(Reestr::className());
            SendMailElk::newElkMail($model,SendMailElk::MAIL_NEW_MEROPR);
            echo Yii::$app->session->setFlash('success', 'Запись успешно обнавлена!');

//            if ($model->date_fact != NULL) {
//                SendMailElk::newElkMail($model,SendMailElk::MAIL_NEW_CLOSE);
//            }

            return $this->redirect($url);
        }

        $this->setCurrentUrl(Reestr::className());


        if (Yii::$app->request->isAjax){
            return $this->renderAjax('modalUpdateevants', [
                'model' => $model,
                'id_department_kontrolled' => $model->getDepartmentListKontr(),
                'id_department_kontrolling' => $model->getDepartmentListKontrl(),
                'id_objects' => $model->getKodOb(),
                'id_significance' => $model->getKodZn(),
                'id_step' => $model->getStep(),
                'cards' => Card::getEmployeesList(),
//                'user' => $model->getUser(),
//                'id_otvetst' => $model->getUser(),
//                'id_kontrol' => $model->getUser(),
                'date_registr' => $model->date_registr,
                'user_last' => $model->getUserById(Yii::$app->user->identity->id),
                'date_time_create' => $model->created_at.' '.$model->time_create,
                'user_first' => $model->getUserById(Yii::$app->user->identity->id),
                'date_time_update' => $model->updated_at.' '.$model->time_update,
            ]);
        } else {
            throw new NotFoundHttpException('The requested page does not exist...');
        }
    }

    public function actionDelete($id)
    {
        try {

            $model = $this->findModel(Reestr::className(), $id);
            $user_id = Yii::$app->user->identity->id;
            $profile = UserProfile::find()->select(['main_department_id'])->where(['user_id' => $user_id])->one();

            if (!AuthHelper::isReestrAdmin()) {

                if (AuthHelper::canEditDepartmentReestr()){
                    if ($model->getDepartmentId($model->id_department_kontrolling) <> $profile->main_department_id && $model->getDepartmentId($model->id_department_kontrolled) <> $profile->main_department_id) {
                        Yii::$app->session->addFlash(
                            'error',
                            Yii::t(
                                'app',
                                'Подразделение пользователя не соответствует подразделению ЭЛК, удаление невозможно', [
//                                $profile->departmentData->getShortCodeShortName(),
//                                $model->departmentData->getShortCodeShortName()
                            ])
                        );
                        $url = $this->getCurrentUrl(Reestr::className());
                        return $this->redirect($url);
                    }
                }
            }
            $this->setCurrentUrl(Reestr::className());
            $this->findModel(Reestr::className(), $id)->delete();
            $url = $this->getCurrentUrl(Reestr::className());
            Yii::$app->session->setFlash('success', Yii::t('app', 'Запись успешно удалена!'));
        } catch (\Exception $exception) {
            Yii::$app->session->setFlash('error', Yii::t('app', 'Внимание: выбранный реестр уже используется! Удаление не возможно!'));
        }
        return $this->redirect('index');
    }

    /**
     * Search reestr information, in Reestr model.
     * @return mixed
     */
    public function actionSearchField()
    {
        $model = new Reestr();

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $id_department_kontrolled = $_POST['date_id'];
        return $model->getInformationDate($id_department_kontrolled);
    }

    /**
     * Search reestr information, in Reestr model.
     * @return mixed
     */
    public function actionSearchDate()
    {
        $model = new Reestr();

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $id_date_plan = $_POST['date_id'];
        $year = Yii::$app->formatter->asDate($id_date_plan, 'y');
        $month = Yii::$app->formatter->asDate($id_date_plan, 'php:m');

        return [ 'year' => $year,
                 'month' => $month
         ];
    }

    /**
     * Search reestr information, in Reestr model.
     * @return mixed
     */
    public function actionSearchOb()
    {
        $model = new Reestr();

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $id_objects = $_POST['ob_id'];
        return $model->getInformationKod($id_objects);
    }

    public function actionDefineStepStatus()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $status_desc = '';
        if (isset($_POST['date_plan'])){
            $status_desc = Step::getStepStatusExecute($_POST['date_plan'],$_POST['date_fact']);
        }
        return ['status_desc' => $status_desc];
    }

    protected function findModel1($id)
    {
        if (($model = Reestr::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }


}