<?php


namespace frontend\modules\elk\models;


use DateTime;
use yii\db\Expression;

class Department extends \frontend\modules\employee\models\Department
{
    public static function getMainDepartments()
    {
        $now = (new DateTime('now'))->format('Y-m-d');
        $max_date = (new DateTime('3000-12-31'))->format('Y-m-d');

        return \frontend\modules\employee\models\Department::find()
            ->andWhere(['<=', 'begin', $now])
            ->andWhere(['>', new Expression("COALESCE(\"end\", '$max_date')"), $now])
            ->andWhere(['in', 'parent_id', ['3001', '3002', '7477', '8220','7479']])
            ->andWhere(['not in', 'id', ['3002']])
            ->andWhere(['not like', 'name', ['УТКиУК']]) //временное решение
            ->orderBy('code')
            ->all();
    }
}