<?php

namespace backend\modules\eavEqu\controllers;

use yarcode\eav\models\Attribute;
use yarcode\eav\modules\backend\Controller;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;

/**
 * Class AttributeController
 * @package yarcode\eav\modules\backend\controllers
 */
class AttributeCategoryController extends Controller
{
    /**
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => $this->getQueryInstance('AttributeCategory'),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'entityName' => $this->getEntityName(),
        ]);
    }

    /**
     * @return mixed
     */
    public function actionCreate()
    {
        /** @var Attribute $model */
        $model = $this->getModelInstance('AttributeCategory');
        //$model->required = true;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
            'entityName' => $this->getEntityName(),
            //'typesQuery' => $this->getQueryInstance('AttributeType'),
        ]);
    }

    /**
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
            'entityName' => $this->getEntityName(),
            //'typesQuery' => $this->getQueryInstance('AttributeType'),
        ]);
    }

    /**
     * @param integer $id
     * @return Attribute the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        $model = $this->getQueryInstance('AttributeCategory')->where(['id' => $id])->one();
        if (!$model) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        return $model;
    }

    /**
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }
}
