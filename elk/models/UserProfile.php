<?php


namespace frontend\modules\elk\models;



use yii\helpers\ArrayHelper;

class UserProfile extends \common\modules\userProfile\models\UserProfile
{

    public static function getProfileByCardId($card_id)
    {
        return self::findOne(['card_id' => $card_id]);
    }

    public static function getProfileByCardId1($card_id)
    {
        return self::findOne(['card_id' => $card_id]);
    }

    public static function getProfileByUserId($user_id)
    {
        return self::findOne(['user_id' => $user_id]);
    }

    public static function getProfileByUserIdD($user_id)
    {
        return self::findOne(['id' => $user_id]);
    }
}
