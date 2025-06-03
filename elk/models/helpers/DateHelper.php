<?php
namespace frontend\modules\elk\models\helpers;

class DateHelper
{

    public static function getDateByTime($date1, $date2)
    {
        return round(abs(strtotime($date2) - strtotime($date1))/86400);
    }

}
