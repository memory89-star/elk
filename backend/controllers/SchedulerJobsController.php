<?php

namespace backend\controllers;

use common\modules\userProfile\models\UserProfile;
use Yii;
use backend\models\SchedulerJobs;
use backend\models\search\SchedulerJobsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;

/**
 * SchedulerJobsController implements the CRUD actions for SchedulerJobs model.
 */
class SchedulerJobsController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['security_admin'],
                    ],
                    [
                        'actions' => ['index', 'create', 'update', 'delete'],
                        'allow' => true,
                        'roles' => ['admin', 'developer'],
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

    /**
     * Lists all SchedulerJobs models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SchedulerJobsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SchedulerJobs model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new SchedulerJobs model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new SchedulerJobs();
        $users = ArrayHelper::map(UserProfile::find()->all(), 'user_id', 'shortName');
        $frequency_types = [SchedulerJobs::FREQ_MINUTELY => Yii::t('app', 'Minutely'),
            SchedulerJobs::FREQ_HOURLY => Yii::t('app', 'Hourly'),
            SchedulerJobs::FREQ_DAILY => Yii::t('app', 'Daily'),
            SchedulerJobs::FREQ_WEEKLY => Yii::t('app', 'Weekly'),
            SchedulerJobs::FREQ_MONTHLY => Yii::t('app', 'Monthly'),
            SchedulerJobs::FREQ_DAYLY_IN_MORNING => Yii::t('app', 'Каждое утро'),
        ];
        $status = [SchedulerJobs::STATUS_ACTIVE => Yii::t('app', 'Active'),
            SchedulerJobs::STATUS_INACTIVE => Yii::t('app', 'Inactive')
        ];

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            /*return $this->redirect(['view', 'id' => $model->id]);*/
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
            'users' => $users,
            'frequency_types' => $frequency_types,
            'status' => $status,
        ]);
    }

    /**
     * Updates an existing SchedulerJobs model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $users = ArrayHelper::map(UserProfile::find()->all(), 'user_id', 'shortName');
        $frequency_types = [SchedulerJobs::FREQ_MINUTELY => Yii::t('app', 'Minutely'),
            SchedulerJobs::FREQ_HOURLY => Yii::t('app', 'Hourly'),
            SchedulerJobs::FREQ_DAILY => Yii::t('app', 'Daily'),
            SchedulerJobs::FREQ_WEEKLY => Yii::t('app', 'Weekly'),
            SchedulerJobs::FREQ_MONTHLY => Yii::t('app', 'Monthly')
        ];
        $status = [SchedulerJobs::STATUS_ACTIVE => Yii::t('app', 'Active'),
            SchedulerJobs::STATUS_INACTIVE => Yii::t('app', 'Inactive')
        ];

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            /*return $this->redirect(['view', 'id' => $model->id]);*/
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
            'users' => $users,
            'frequency_types' => $frequency_types,
            'status' => $status,
        ]);
    }

    /**
     * Deletes an existing SchedulerJobs model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the SchedulerJobs model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SchedulerJobs the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SchedulerJobs::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
