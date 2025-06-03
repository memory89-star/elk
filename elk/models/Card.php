<?php


namespace frontend\modules\elk\models;


use common\modules\userProfile\models\UserExt;
use DateTime;
use frontend\modules\employee\models\Department;
use frontend\modules\employee\models\Staffpos;
use Yii;
use yii\db\Expression;
use yii\db\Query;
use yii\helpers\ArrayHelper;

class Card extends \frontend\modules\employee\models\Card
{

    public static function getEmployeesList($date = null)
    {
        if ($date){
            $date_seach = $date;
        }else{
            $date_seach = (new DateTime('now'))->format('Y-m-d');
        }

        $max_date = (new DateTime('3000-12-31'))->format('Y-m-d');

        $out = new Query();
        $out->addSelect([
            'emp_card.id as id',
            new Expression("CONCAT(emp_card.secondname, ' ', emp_card.firstname, ' ', emp_card.thirdname) as name"),
        ])->from('emp_card')
            ->leftJoin(['emp_movement mov'], 'mov.stabnum = emp_card.stabnum')
//            ->leftJoin('staffpos', 'staffpos.staffpos_item_id = movement.staffpos_item_id')
//            ->where(['and', ['<=','movement.begin', $dateEnd], ['>=','movement.end', $dateEnd]])
            ->andWhere(['<=', 'mov.begin', $date_seach])
            ->andWhere(['>=', new Expression("COALESCE(mov.end, '$max_date')"), $date_seach])
            ->andWhere(['!=', 'mov.staffpos_item_id', '99999999'])
            ->orderBy('name');
        $mass = $out->all();
        return ArrayHelper::map($mass,'id','name');
    }


    public static function getFioByCardId($card_id)
    {
        $query = self::find()->select(['secondname','firstname','thirdname'])->where(['id' => $card_id])->one();
        return ($query) ? ($query->secondname.'  '.$query->firstname.' '.$query->thirdname) : '';
    }

    public function getFio()
    {
        return trim($this->secondname.'  '.$this->firstname.' '.$this->thirdname);
    }

    /**
     * Get the employee’s staff position to the current date
     * @return array|null|\yii\db\ActiveRecord
     */
    public function getStaffPosOnDate($date = null)
    {
        if ($date){
            $date_seach = $date;
        }else{
            $date_seach = (new DateTime('now'))->format('Y-m-d');
        }
        $max_date = (new DateTime('3000-12-31'))->format('Y-m-d');

        return Staffpos::find()
            ->alias('sfp')
            ->innerJoin(['{{%emp_movement}} mov'], 'mov.staffpos_item_id = sfp.staffpos_item_id')
            ->andWhere(['=', 'mov.stabnum', $this->stabnum])
            ->andWhere(['<=', 'mov.begin', $date_seach])
            ->andWhere(['>', new Expression("COALESCE(mov.end, '$max_date')"), $date_seach])
            ->andWhere(['!=', 'sfp.staffpos_item_id', '99999999'])
            ->one();
    }



}
