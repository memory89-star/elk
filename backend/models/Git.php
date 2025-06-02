<?php

namespace backend\models;

use yii\base\Model;

/**
 * Git for run console command
 */
class Git extends Model
{
    public $console_command;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['console_command'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'console_command' => 'Git Code',
        ];
    }

}
