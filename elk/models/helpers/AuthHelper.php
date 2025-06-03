<?php
namespace frontend\modules\elk\models\helpers;

use common\modules\userProfile\models\UserProfile;
use common\widgets\Alert;
use frontend\modules\elk\models\DepartmentData;
use frontend\modules\elk\models\Reestr;
use Yii;

class AuthHelper
{
    const ELK_USER_DEP = 'elk_user_dep';
    const ELK_ADMIN = 'elk_admin';
    const ELK_USER_GXK = 'elk_user_gxk';

    public static function getRoles()
    {
        return [
            'elk_admin' => 'Администратор ЭЛК',
            'elk_user_dep' => 'Ответсвтенный пользователь подразделения',
            'elk_user_gxk' => 'Ответсвтенный пользователь предприятия',
        ];
    }


    const EDIT_DEPARTMENT_REESTR_ROLES = [
        self::ELK_USER_DEP,
    ];

    const REESTR_ADMIN_ROLES = [
        self::ELK_ADMIN,
        self::ELK_USER_GXK,
    ];




    public static function getUserRoles($user_id)
    {
        $roles = Yii::$app->authManager->getRolesByUser($user_id);
        $roles = array_keys($roles);
        $roles = array_filter($roles, function ($val){
            return ($val != 'guest');
        });
//        $roles = array_diff($roles, ['guest']); // любой из двух вариантов
        $roles = array_values($roles);

        return $roles;
    }

    public static function canEditDepartmentReestr()
    {
        $user = Yii::$app->user;
        foreach (self::EDIT_DEPARTMENT_REESTR_ROLES as $role) {
            if ($user->can($role)) return true;
        }
        return false;
    }

    public static function isReestrAdmin()
    {
        $user = Yii::$app->user;
        foreach (self::REESTR_ADMIN_ROLES as $role) {
            if ($user->can($role)) return true;
        }
        return false;
    }



}