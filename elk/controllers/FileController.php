<?php

namespace frontend\modules\elk\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\helpers\BaseFileHelper;
use frontend\modules\elk\models\FileModel;
use frontend\modules\elk\models\File;



class FileController extends Controller
{
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

    public function actionIndex($pathSubDir = '')
    {
        $model = new File();

        $dirPath = "";
        if (array_key_exists('elkFilesPath',Yii::$app->params))
            $dirPath = Yii::$app->params['elkFilesPath'];
        $dirPath .= '/'.$pathSubDir;

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('index', [
                'pathSubDir' => $pathSubDir,
                'arrExt' => File::getArrayExtension(),
                'modelFile' => new FileModel(),
                'previewImg' => array_key_exists('elkFilesPath', Yii::$app->params) ? $model->previewImg($dirPath) : null,
            ]);
        } else {
            return $this->render('index', [
                'pathSubDir' => $pathSubDir,
                'arrExt' => File::getArrayExtension(),
                'modelFile' => new FileModel(),
                'previewImg' => array_key_exists('elkFilesPath', Yii::$app->params) ? $model->previewImg($dirPath) : null,
            ]);
        }
    }

    public function actionUpload($pathSubDir = '')
    {
        $model = new File();
        $modelFile = new FileModel();

        $dirPath = Yii::$app->params['elkFilesPath'].'/'.$pathSubDir;

        if (Yii::$app->request->post()) {
            if (!BaseFileHelper::createDirectory($dirPath)) { 
                return 'Could not create directory ' . $dirPath; 
            }
            if (isset(UploadedFile::getInstance($modelFile,'fileName')->baseName)) {
                $filePath = $dirPath.'/'.UploadedFile::getInstance($modelFile,'fileName')->baseName.'.'.UploadedFile::getInstance($modelFile,'fileName')->extension;
                $paramsPost = Yii::$app->request->post();
                if (UploadedFile::getInstance($modelFile,'fileName')->saveAs($filePath)) {

                    return json_encode(['success' => 'Файл ' . UploadedFile::getInstance($modelFile, 'fileName')->baseName . '.' . UploadedFile::getInstance($modelFile, 'fileName')->extension . ' успешно сохранен !!!']);
                }
                else { 
                    return 'Файл ' . UploadedFile::getInstance($modelFile,'fileName')->baseName . '.' . UploadedFile::getInstance($modelFile,'fileName')->extension.' не сохранен в каталоге !!!'; 
                }
            }
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionDelete()
    {
//        $model = new File();
        $ret = false;
        $fileOne = null;

        $str = File::getClearPath(Yii::$app->request->post('key'));
        $dirPath = File::getDirPath($str);
        $fileName = File::getFileName(File::getFilePath($str));
        $fileExt = File::getFileExt($str);

        if (isset($fileOne)) {
            if (!$fileOne->delete()) $ret = 'Информация о файле ' . $fileName . '.' . $fileExt.' отсутствует в базе !!!';
        }

        try {
            $unl = unlink($dirPath . '/' . $fileName . '.' . $fileExt);
            if (!$ret) $ret = json_encode(['success' => 'Файл ' . $fileName . '.' . $fileExt.' успешно удален !!!']);
        } catch (ErrorException $e) {
            if (!$ret) $ret = 'Информация о файле удалена из базы, но в каталоге отсутствовал файл ' . $fileName . '.' . $fileExt.' !!!';
        }

        return $ret;
    }

    public function actionDownload($path = '', $fileName = '', $key = '')
    {
        return Yii::$app->response->sendFile($path.$key.$fileName, $fileName, ['inline' => true]);
    }

    public function actionViewLink($pathSubDir = '')
    {
        $model = new File();

        $dirPath = "";
        if (array_key_exists('elkFilesPath',Yii::$app->params))
            $dirPath = Yii::$app->params['elkFilesPath'];

        $dirPath .= '/'.$pathSubDir;

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('viewLink', [
                'arrExt' => File::getArrayExtension(),
                'modelFile' => new FileModel(),
                'previewImg' => array_key_exists('elkFilesPath', Yii::$app->params) ? $model->previewImg($dirPath) : null,
            ]);
        }
    }
}
