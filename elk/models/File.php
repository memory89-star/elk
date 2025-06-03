<?php

namespace frontend\modules\elk\models;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\FileHelper;
use yii\helpers\BaseFileHelper;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;
use yii\db\Expression;

class File extends \yii\db\ActiveRecord
{
    public $doc_files_extensions = 'pdf,jpeg,jpg,png,bmp,docx,doc,xlsx';

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors = ArrayHelper::merge($behaviors, [
            [
                'class' => TimestampBehavior::className(),
                'value' => new Expression('NOW()'),
            ],
        ]);
        return $behaviors;
    }
    /**
     * Функция предпросмотра
     * @param string $path
     */
    public function previewImg($path)
    {
        $names = [];
        $preview = [];
        $res = [];

        if (!BaseFileHelper::createDirectory($path)) {
            Yii::$app->getSession()->addFlash('error', 'Could not create directory ' . $path);
            return false;
        }

        $files = FileHelper::findFiles($path);

        foreach ($files as $index => $file) {

            $str = $this->getClearPath($file);
            $dirPath = $this->getDirPath($str);
            $nameFile = '/'.$this->getFilePath($str);

            $names[] = ['key' => $dirPath.$nameFile, 'caption' => $this->getFilePath($str)];
            $nameExt = $this->getFileExt($str);

            if ( $nameExt == 'jpg' || $nameExt == 'jpeg' || $nameExt == 'png') {
                $preview[] = "<img class = 'pdf-preview' style='width: auto; height: auto; max-width: 100%; max-height: 100%; image-orientation: from-image;' src = ".Url::to(['download', 'path' => $dirPath, 'fileName' => $nameFile])."></img>";
            } else {
                if ( $nameExt == 'pdf' ) {
                    $preview[] = "<embed class = 'pdf-viewer' style='width: 120%; height: 100%; max-width: 100%; max-height: 100%;' src = ".Url::to(['download', 'path' => $dirPath, 'fileName' => $nameFile])."></embed>";
                } else {
                    $preview[] = Html::a('<span class = "file-other-icon"><i class="fas fa-file"></i></span> ', Url::to(['download', 'path' => $dirPath, 'fileName' => $nameFile]));
                }
            }

            $res = [
                $names,
                $preview
            ];
        }

        return $res;
    }

    /**
     * Функция очищает путь до файла по входящему параметру
     * @param string $path
     */
    public static function getClearPath($path)
    {
        return preg_replace('/[\/]+/','/',preg_replace('/[\\\\]/', '/',$path));
    }

    /**
     * Функция возвращает путь по входящему параметру
     * @param string $path
     */
    public static function getDirPath($path)
    {
        $dir = "";
        if (strrpos($path, '/') > 0) $dir = substr($path, 0, strrpos($path, '/'));
        return $dir;
    }

    /**
     * Функция возвращает путь до файла по входящему параметру
     * @param string $path
     */
    public static function getFilePath($path)
    {
        $file = "";
        if (strrpos($path, '/') > 0) $file = substr($path, strrpos($path, '/') + 1);
        return $file;
    }

    /**
     * Функция возвращает id файла по входящему параметру
     * @param string $path
     */
    public static function getFileExt($path)
    {
        $ext = "";
        if (strrpos($path, '.') > 0) $ext = substr($path, strrpos($path, '.') + 1);
        return $ext;
    }

    /**
     * Функция возвращает имя файла по входящему параметру
     * @param string $name
     */
    public static function getFileName($name)
    {
        $fileName = "";
        if (strrpos($name, '.') > 0) $fileName = substr($name, 0, strrpos($name, '.'));
        return $fileName;
    }

    /**
     * Функция возвращает массив с разрешенными форматами файлов
     */
    public static function getArrayExtension() {
        return [
            'jpg',
            'jpeg',
            'png',
            'bmp',
            'pdf',
            'xlsx',
            'docx',
        ];
    }

    /**
     * Функция возвращает по файлу информацию по входящим параметрам
     * @param string $fileName
     * @param string $fileExt
     */
    public function getInformationFile($fileName, $fileExt) {
        return File::find()->where(['file_name' => $fileName, 'file_ext' => $fileExt])->one();
    }

    /**
     * Функция возвращает статичный путь руководства пользователя
     */
    public static function getStaticPathUserGuide() {
        return "UserGuide";
    }

    /**
     * Функция возвращает статичный путь плакатов
     */
    public static function getStaticPathPosters() {
        return "Posters";
    }
}