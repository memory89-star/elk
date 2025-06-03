<?php

namespace frontend\modules\elk\controllers;

use frontend\modules\elk\models\ReestrPrint;
use kartik\mpdf\Pdf;
use PhpParser\Node\Expr\Isset_;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use DateTime;
use yii\db\Expression;
use yii\helpers\Html;

/**
 * ReestrPrintController implements the CRUD actions for ReestrPrint model.
 */
class ReestrPrintController extends BasesemController
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
     * Печать формы реестра ЭЛК за период
     * @return mixed
     */
    public function actionIndex()
    {
        $model = new ReestrPrint();

        if ($model->load(Yii::$app->request->post())) {
            $data = $model->getData($model->departnemt_kon_begin, $model->departnemt_kon_end, $model->departnemt_begin, $model->departnemt_end, $model->date_begin, $model->date_end);
            $counter = count($data);
            if ($counter != 0) {
                Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
                Yii::$app->response->headers->add('Content-Type', 'application/pdf');

                $pdf = new Pdf([
                    'mode' => Pdf::MODE_UTF8,
                    'format' => Pdf::FORMAT_A4,
                    'orientation' => Pdf::ORIENT_LANDSCAPE,
                    'content' => $this->renderPartial('report', [
                        'data' => $data,
                        'counter' => $counter,
                        'model' => $model,
                    ]),
                    'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
                    'cssInline' => '.img-circle {border-radius: 50%;}',
                    'options' => [
                        'title' => 'Печать реестра ЭЛК',
                        'subject' => 'PDF',
                    ],
                ]);
                return $pdf->render();
            } else {
                Yii::$app->getSession()->setFlash('error', 'Данные не найдены');
                return $this->redirect('index');
            }
        }

        return $this->render('index', [
            'model' => $model,
        ]);
    }
}