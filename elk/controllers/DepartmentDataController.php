<?php

namespace frontend\modules\elk\controllers;

use frontend\modules\elk\models\Card;
use Yii;
use frontend\modules\employee\models\Department;
use common\modules\userProfile\models\UserProfile;
use common\models\User;
use frontend\modules\elk\models\DepartmentData;
use frontend\modules\elk\models\search\DepartmentDataSearch;
use yii\base\BaseObject;
use yii\bootstrap4\ActiveForm;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Response;

/**
 * DepartmentDataController implements the CRUD actions for DepartmentData model.
 */
class DepartmentDataController extends BasesemController
{
    /**
     * @inheritdoc
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

    /**
     * Lists all DepartmentData models.
     * @return mixed
     */
    public function actionIndex()
    {
        $model = new DepartmentData();
        $searchModel = new DepartmentDataSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'block' => $model->block(),
        ]);
    }

    /**
     * Displays a single DepartmentData model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = new DepartmentData();
        $model = $this->findModel(DepartmentData::className(), $id);
//        $model->user_last = Yii::$app->user->identity->username;
//        $model->user_first = Yii::$app->user->identity->username;

        $model->user_last = Yii::$app->user->identity->id;
        $model->user_first = Yii::$app->user->identity->id;

        if (Yii::$app->request->isAjax){
            return $this->renderAjax('modalView', [
                'model' => $model,
                'block' => $model->block,
                'user_last' => $model->getName($model->user_first),
                'date_time_create' => $model->created_at.' '.$model->time_create,
                'user_first' => $model->getName($model->user_last),
                'date_time_update' => $model->updated_at.' '.$model->time_update,
            ]);
        } else {
            throw new NotFoundHttpException('The requested page does not exist...');
        }
    }

    /**
     * Creates a new DepartmentData model.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new DepartmentData();
//        $model->user_last = Yii::$app->user->identity->username;
//        $model->user_first = Yii::$app->user->identity->username;

        $model->user_last = Yii::$app->user->identity->id;
        $model->user_first = Yii::$app->user->identity->id;

        $model->created_at = Date('Y-m-d'); //('d.m.Y')
        $model->time_create = Date('H:i:s');
        $model->updated_at = Date('Y-m-d'); //('d.m.Y')
        $model->time_update = Date('H:i:s');
        $model->block = '0';
        $model->doc_num_max = '0';

        $emp_department_type = [
            'Контролируемое' => 'Контролируемое',
            'Контролирующее' => 'Контролирующее',
        ];

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $url = $this->getCurrentUrl(DepartmentData::className());
            echo Yii::$app->session->setFlash('success', 'Запись успешно добавлена!');
            return $this->redirect($url);
        }

        $this->setCurrentUrl(DepartmentData::className());

        if (Yii::$app->request->isAjax){
            return $this->renderAjax('modalForm', [
                'model' => $model,
                'emp_department_id' => $model->getDepartmentList(),
                'emp_department_type' => $emp_department_type,
                'block' => ['0'=>'Нет', '1'=>'Да'],
//                'user' => $model->getUser(),
                'cards' => Card::getEmployeesList(),
                'user_last' => $model->getUserById(Yii::$app->user->identity->id),
                'date_time_create' => $model->created_at.' '.$model->time_create,
                'user_first' => $model->getUserById(Yii::$app->user->identity->id),
                'date_time_update' => $model->updated_at.' '.$model->time_update,
            ]);
        } else {
            throw new NotFoundHttpException('The requested page does not exist...');
        }
    }

    /**
     * Updates an existing DepartmentData model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel(DepartmentData::className(), $id);
        $modelOld = $this->findModel(DepartmentData::className(), $id);

//        $model->user_last = Yii::$app->user->identity->username;
        $model->user_last = Yii::$app->user->identity->id;

        $model->created_at = Date('Y-m-d'); //('d.m.Y')
        $model->time_create = Date('H:i:s');
        $model->updated_at = Date('Y-m-d'); //('d.m.Y')
        $model->time_update = Date('H:i:s');

        $block = $model->block();

        $emp_department_type = [
            'Контролируемое' => 'Контролируемое',
            'Контролирующее' => 'Контролирующее',
        ];

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $url = $this->getCurrentUrl(DepartmentData::className());
            echo Yii::$app->session->setFlash('success', 'Запись успешно обнавлена!');
            return $this->redirect($url);
        }

        $this->setCurrentUrl(DepartmentData::className());


        if (Yii::$app->request->isAjax){
            return $this->renderAjax('modalForm', [
                'model' => $model,
                'emp_department_id' => $model->getDepartmentList(),
                'emp_department_type' => $emp_department_type,
                'block' => ['0'=>'Нет', '1'=>'Да'],
//                'user' => $model->getUser(),
                'cards' => Card::getEmployeesList(),
                'user_last' => $model->getUserById(Yii::$app->user->identity->id),
                'date_time_create' => $model->created_at.' '.$model->time_create,
                'user_first' => $model->getUserById(Yii::$app->user->identity->id),
                'date_time_update' => $model->updated_at.' '.$model->time_update,
            ]);
        } else {
            throw new NotFoundHttpException('The requested page does not exist...');
        }
    }


       /**
     * Search department information, in Department model.
     * @return mixed
     */
    public function actionSearchField()
    {
        $model = new DepartmentData();

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $emp_department_id = $_POST['dep_id'];
        return $model->getInformationDepartment($emp_department_id);
    }





}
