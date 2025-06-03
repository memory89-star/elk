<?php

namespace frontend\modules\elk\controllers;

use common\modules\userProfile\models\UserProfile;
use frontend\modules\elk\models\Card;
use frontend\modules\elk\models\Discrepancy;
use frontend\modules\elk\models\Events;
use frontend\modules\elk\models\helpers\AuthHelper;
use frontend\modules\elk\models\Reestr;
use frontend\modules\elk\models\helpers\SendMailElk;
use frontend\modules\elk\models\Kod;
use Yii;
use frontend\modules\elk\models\search\EventsSearch;
use yii\base\BaseObject;
use yii\bootstrap4\ActiveForm;
use kartik\mpdf\Pdf;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\web\Response;


class EventsController extends BasesemController
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
        $searchModel = new EventsSearch();
        $searchModel->search([$searchModel->formName()=>['id_discrepancy' => $id]]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $events = Discrepancy::find()->all();
        $id_discrepancy = ArrayHelper::map($events, 'id', 'discrepancy');

        $model = new Events();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'id' => $id,
            'id_discrepancy' => $id_discrepancy,
        ]);
    }

//    public function actionView($id)
//    {
//        $modelStep = new Discrepancy();
//        $model = $this->findModel($id);
//        $model->user_last = Yii::$app->user->identity->username;
//        $model->user_first = Yii::$app->user->identity->username;
//
//        if (Yii::$app->request->isAjax){
//            return $this->renderAjax('modalView', [
//                'model' => $model,
//            ]);
//        } else {
//            return $this->render('modalView', [
//                'model' => $model,
//            ]);
//        }
//    }

    public function actionCreate($id)
    {
        $model = new Events();
        $model->id_discrepancy = $id;
//        $model->user_last = Yii::$app->user->identity->username;
//        $model->user_first = Yii::$app->user->identity->username;
        $model->user_last = Yii::$app->user->identity->id;
        $model->user_first = Yii::$app->user->identity->id;

        $user_id = Yii::$app->user->identity->id;
        $profile = UserProfile::find()->select(['main_department_id'])->where(['user_id' => $user_id])->one();

        if (!AuthHelper::isReestrAdmin()) {

            if (AuthHelper::canEditDepartmentReestr()){
                if ($model->getDepartmentId($model->id_discrepancy) <> $profile->main_department_id) {
                    Yii::$app->session->addFlash(
                        'error',
                        Yii::t(
                            'app',
                            'Подразделение пользователя не соответствует подразделению ЭЛК или не является "Контролируемым", добавление невозможно', [
//                                $profile->departmentData->getShortCodeShortName(),
//                                $model->departmentData->getShortCodeShortName()
                        ])
                    );
                    $url = $this->getCurrentUrl(Events::className());
                    return $this->redirect($url);
                }
            }
        }

        $model->created_at = Date('Y-m-d'); //('d.m.Y')
        $model->time_create = Date('H:i:s');
        $model->updated_at = Date('Y-m-d'); //('d.m.Y')
        $model->time_update = Date('H:i:s');

        $events = Discrepancy::find()->all();
        $id_discrepancy = ArrayHelper::map($events, 'id', 'discrepancy');

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $url = $this->getCurrentUrl(Events::className());
            echo Yii::$app->session->setFlash('success', 'Запись успешно добавлена!');
            SendMailElk::newElkMail($model,SendMailElk::MAIL_NEW_EVENTS);
            return $this->redirect($url);
        }

        $this->setCurrentUrl(Events::className());

        if (Yii::$app->request->isAjax){
            return $this->renderAjax('modalForm', [
                'model' => $model,
                'id_discrepancy' => $id_discrepancy,
//                'id_otvetst' => $model->getUser(),
//                'id_kontrol' => $model->getUser(),
                'cards' => Card::getEmployeesList(),
                'user_last' => $model->getUserById(Yii::$app->user->identity->id),
                'date_time_create' => $model->created_at.' '.$model->time_create,
                'user_first' => $model->getUserById(Yii::$app->user->identity->id),
                'date_time_update' => $model->updated_at.' '.$model->time_update,
                'id' => $id,
            ]);
        } else {
            throw new NotFoundHttpException('The requested page does not exist...');
        }
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel(Events::className(), $id);
        $modelOld = $this->findModel(Events::className(), $id);

//        $model->user_last = Yii::$app->user->identity->username;
        $model->user_last = Yii::$app->user->identity->id;

        $user_id = Yii::$app->user->identity->id;
        $profile = UserProfile::find()->select(['main_department_id'])->where(['user_id' => $user_id])->one();

        if (!AuthHelper::isReestrAdmin()) {

            if (AuthHelper::canEditDepartmentReestr()){
                if ($model->getDepartmentId($model->id_discrepancy) <> $profile->main_department_id) {
                    Yii::$app->session->addFlash(
                        'error',
                        Yii::t(
                            'app',
                            'Подразделение пользователя не соответствует подразделению ЭЛК или не является "Контролируемым", редактирование невозможно', [
//                                $profile->departmentData->getShortCodeShortName(),
//                                $model->departmentData->getShortCodeShortName()
                        ])
                    );
                    $url = $this->getCurrentUrl(Events::className());
                    return $this->redirect($url);
                }
            }
        }

        $model->created_at = Date('Y-m-d'); //('d.m.Y')
        $model->time_create = Date('H:i:s');
        $model->updated_at = Date('Y-m-d'); //('d.m.Y')
        $model->time_update = Date('H:i:s');

        $events = Discrepancy::find()->all();
        $id_discrepancy = ArrayHelper::map($events, 'id', 'discrepancy');

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $url = $this->getCurrentUrl(Events::className());
            echo Yii::$app->session->setFlash('success', 'Запись успешно обнавлена!');
            return $this->redirect($url);
        }

        $this->setCurrentUrl(Events::className());


        if (Yii::$app->request->isAjax){
            return $this->renderAjax('modalForm', [
                'model' => $model,
                'id_discrepancy' => $id_discrepancy,
//                'id_otvetst' => $model->getUser(),
//                'id_kontrol' => $model->getUser(),
                'cards' => Card::getEmployeesList(),
                'user_last' => $model->getUserById(Yii::$app->user->identity->id),
                'date_time_create' => $model->created_at.' '.$model->time_create,
                'user_first' => $model->getUserById(Yii::$app->user->identity->id),
                'date_time_update' => $model->updated_at.' '.$model->time_update,
                'id' => $id,
            ]);
        } else {
            throw new NotFoundHttpException('The requested page does not exist...');
        }
    }

    public function actionDelete($id)
    {
        try {
            $model = $this->findModel(Events::className(), $id);
            $user_id = Yii::$app->user->identity->id;
            $profile = UserProfile::find()->select(['main_department_id'])->where(['user_id' => $user_id])->one();

            if (!AuthHelper::isReestrAdmin()) {

                if (AuthHelper::canEditDepartmentReestr()){
                    if ($model->getDepartmentId($model->id_discrepancy) <> $profile->main_department_id) {
                        Yii::$app->session->addFlash(
                            'error',
                            Yii::t(
                                'app',
                                'Подразделение пользователя не соответствует подразделению ЭЛК или не является "Контролируемым", удаление невозможно', [
//                                $profile->departmentData->getShortCodeShortName(),
//                                $model->departmentData->getShortCodeShortName()
                            ])
                        );
                        $url = $this->getCurrentUrl(Events::className());
                        return $this->redirect($url);
                    }
                }
            }
            $this->setCurrentUrl(Events::className());
            $this->findModel(Events::className(), $id)->delete();
            $url = $this->getCurrentUrl(Events::className());
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
        if ($session->has('EventsSearch')) {
            $session->remove('EventsSearch');
        }
        if ($session->has('EventsSearchSort')) {
            $session->remove('EventsSearchSort');
        }
        return $this->redirect(['index', 'id' => $id]);
    }

    /**
     * Search department information, in Department model.
     * @return mixed
     */
    public function actionSearchField()
    {
        $model = new Events();

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $events = $_POST['events_id'];
        return $model->getInformationDepartment($events);
    }

    public function actionDefineStepStatus()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $status_desc = '';
        if (isset($_POST['date_plan'])){
            $status_desc = Events::getStepStatusExecute($_POST['date_plan'],$_POST['date_fact']);
        }
        return ['status_desc' => $status_desc];
    }

    /**
     * Search reestr information, in Reestr model.
     * @return mixed
     */
    public function actionSearchDate()
    {
        $model = new Events();

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $id_date_plan = $_POST['date_id'];
        $year = Yii::$app->formatter->asDate($id_date_plan, 'y');
        $month = Yii::$app->formatter->asDate($id_date_plan, 'php:m');

        return [ 'year' => $year,
            'month' => $month
        ];
    }


}