<?php

namespace frontend\modules\elk\models;

use Yii;
use yii\base\Model;

class FileModel extends Model
{

    public $fileName;

    public function rules()
    {
        return [
            [['fileName'], 'file', 'skipOnEmpty' => false, 'maxFiles' => 10, 'checkExtensionByMimeType' => false],
        ];
    }

    public function attributeLabels()
    {
        return [
            'fileName' => '',
        ];
    }

}