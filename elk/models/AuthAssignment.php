<?php


namespace frontend\modules\elk\models;


use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

class AuthAssignment extends ActiveRecord
{

    public static function tableName()
    {
        return 'auth_assignment';
    }

    public static function getUserListByRole($role)
    {
        return ArrayHelper::getColumn(ArrayHelper::toArray(self::find()->select(['user_id'])->where(['item_name' => $role])->all()),'user_id');
    }

}
