<?php
namespace frontend\modules\elk\models;

use common\modules\userProfile\models\UserProfile;
use frontend\modules\elk\models\DepartmentData;
use frontend\modules\elk\models\Reestr;
use Yii;
use yii\db\Query;
use yii\base\Model;
use yii\helpers\ArrayHelper;

/**
 * @property number $departnemt_kon_begin Контролируемое подразделение с
 * @property number $departnemt_kon_end Контролируемое подразделение по
 * @property number $departnemt_begin Контролирующее подразделение с
 * @property number $departnemt_end Контролирующее подразделение по
 * @property string $date_begin Дата выявления с
 * @property string $date_end Дата выявления по
 */
class ReestrPrint extends \yii\db\ActiveRecord
{
    public $departnemt_kon_begin;
    public $departnemt_kon_end;
    public $departnemt_begin;
    public $departnemt_end;
    public $date_begin;
    public $date_end;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['departnemt_begin', 'departnemt_end','departnemt_kon_begin', 'departnemt_kon_end', 'date_begin', 'date_end'], 'required'],
            ['date_end', 'compare', 'compareAttribute' => 'date_begin', 'operator' => '>=', 'message' => 'Дата выявления "С" должна быть меньше, чем Дата выявления "По".'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'departnemt_kon_begin' => Yii::t('app', 'Контролируемое подразделение с'),
            'departnemt_kon_end' => Yii::t('app', 'Контролируемое подразделение по'),
            'departnemt_begin' => Yii::t('app', 'Контролирующее подразделение с'),
            'departnemt_end' => Yii::t('app', 'Контролирующее подразделение по'),
            'date_begin' => Yii::t('app', 'Дата выявления с'),
            'date_end' => Yii::t('app', 'Дата выявления по'),
        ];
    }

    /**
     * Функция получения списка подразделений
     * @return array|null|\yii\db\ActiveRecord
     */
    public function getDepartment()
    {
        $query = DepartmentData::find()->orderBy('emp_department_code ASC')->where(['=' ,'block', '0'])->andWhere(['emp_department_type' => 'Контролируемое'])->all();
        return  ArrayHelper::map($query, 'id', function($one) {
            return $one->emp_department_code.' '.$one->emp_department_name;
        });
    }

    /**
     * Функция получения списка подразделений
     * @return array|null|\yii\db\ActiveRecord
     */
    public function getDepartmentl()
    {
        $query = DepartmentData::find()->orderBy('emp_department_code ASC')->where(['=' ,'block', '0'])->andWhere(['emp_department_type' => 'Контролирующее'])->all();
        return  ArrayHelper::map($query, 'id', function($one) {
            return $one->emp_department_code.' '.$one->emp_department_name;
        });
    }

    /**
     * Функция возвращает информацию по реестру по входящим параметрам
     * @param number $departnemt_begin
     * @param number $departnemt_end
     * @param number $departnemt_kon_begin
     * @param number $departnemt_kon_end
     * @param string $date_begin
     * @param string $date_end
     * @return array|null|\yii\db\ActiveRecord
     */
    public function getData($departnemt_kon_begin, $departnemt_kon_end, $departnemt_begin, $departnemt_end, $date_begin, $date_end)
    {
        $query = \frontend\modules\elk\models\DepartmentData::find()->orderBy('emp_department_code ASC')->where(['=' ,'block', '0'])->all();
        $departments = ArrayHelper::map($query, 'id', function($one) {
            return ['name'=>$one->emp_department_code.' '.$one->emp_department_name, 'id'=>$one->id];
        });
        sort($departments);
        $limits = [];
        $index = 0;
        foreach ($departments as $key=>$department) {
            if ($department['id'] == $departnemt_kon_begin || $department['id'] == $departnemt_kon_end ) {
                array_push($limits, $index);
            };
            $index++;
        }

        sort($departments);
        $limits1 = [];
        $index1 = 0;
        foreach ($departments as $key=>$department) {
            if ($department['id'] == $departnemt_begin || $department['id'] == $departnemt_end ) {
                array_push($limits1, $index1);
            };
            $index1++;
        }
        $result_array = ArrayHelper::map(array_slice($departments, min($limits), max($limits)-min($limits)+1), 'id', function($one) {
            return $one['id'];
        });
        $result_array2 = ArrayHelper::map(array_slice($departments, min($limits1), max($limits1)-min($limits1)+1), 'id', function($one) {
            return $one['id'];
        });

        return Reestr::find()->where(['IN', 'id_department_kontrolled', $result_array])->andwhere(['IN', 'id_department_kontrolling', $result_array2])->andwhere(['BETWEEN', 'left(cast(created_at as varchar),10)', $date_begin, $date_end])->all();

    }

    /* Коррекционые действия для отчета, Таблица 1 *******************************************************************************/
    public function getIncongruityStr($id)
    {
        $query = Reestr::find()->select(['id'])->where(['id_reportcorr' => $id])->all();
        return ArrayHelper::getColumn($query, 'id_discrepen');
    }

    public function getDepartmentKontr($id)
    {
        $query = DepartmentData::find()->select(['emp_department_code', 'emp_department_name'])->where(['id' => $id])->one();
        return $query->emp_department_code. ' ' .$query->emp_department_name;
    }

    public function getKod($id)
    {
        $query = Kod::find()->select(['kod_objects'])->where(['id' => $id])->one();
        return $query->kod_objects;
    }

    public function getZn($id)
    {
        $query = Significance::find()->select(['name'])->where(['id' => $id])->one();
        return $query->name;
    }

    public function getSrokPlan($id)
    {
        $query = Reestr::find()->select(['date_plan'])->where(['id' => $id])->one();
        return (Yii::$app->formatter->asDate($query->date_plan)!= NULL)?Yii::$app->formatter->asDate($query->date_plan) : '-';
    }

    public function getSrokFakt($id)
    {
        $query = Reestr::find()->select(['date_fact'])->where(['id' => $id])->one();
        return (Yii::$app->formatter->asDate($query->date_fact) != NULL)?Yii::$app->formatter->asDate($query->date_fact) : '-';
    }

    public static function getFioByCardId($card_id)
    {
        $query = Card::find()->select(['secondname','firstname','thirdname'])->where(['id' => $card_id])->one();
        return ($query) ? ($query->secondname.'  '.$query->firstname.' '.$query->thirdname) : '-';
    }

    //статус Коррекции
    public static function getStepStatus($id)
    {
        $query = Reestr::find()->select(['date_plan', 'date_fact'])->where(['id' => $id])->one();

        $status = 0;
        if ($query->date_plan != NULL) {

            $today = new \DateTime();
            $today = $today->format('Y-m-d');
            if($query->date_fact == NULL){
                if(strtotime($query->date_plan) >= strtotime($today)){
                    $status = 'В работе';
                }elseif(strtotime($query->date_plan) < strtotime($today)){
                    $status = 'Не выполнено';
                }
            }else{
                if(strtotime($query->date_plan) >= strtotime($query->date_fact)){
                    $status = 'Просрочено';
                }else{
                    $status = 'Выполнено в срок';
                }
            }
            return $status;
        } else  return '';
    }

        public static function getEventsKolID($id)
    {
        $query = (new Query())
            ->select ([
                'elk_reestr.id as id',
                'elk_reasons_discrep.id as id_discrep',
                'elk_reasons_discrep.discrepancy as discrepancy',
                'elk_events.events as events',
                'elk_events.id as id_events',
                'elk_events.id_otvetst as id_otvetst',
                'elk_events.id_kontrol as id_kontrol',
                'elk_events.date_plan as date_plan',
                'elk_events.date_fact as date_fact',
            ])
            ->from(['elk_reestr'])
            ->leftJoin('elk_reasons_discrep', 'elk_reasons_discrep.id_reestr = elk_reestr.id')
            ->leftJoin('elk_events', 'elk_events.id_discrepancy = elk_reasons_discrep.id')
            ->where(['elk_reasons_discrep.id_reestr' => $id]);
            $array_point = $query->all(\Yii::$app->elk);

            foreach ($array_point as $index =>$action ){
                $array_point[$index]['id_discrep'] = ($action['id_discrep']);
            }

            return $array_point;

    }

    //Кол
    public static function getKolDiscr($id)
    {
        $query = new Query();
        $query->addSelect([
            'elk_reasons_discrep.discrepancy as kolDiscr',
        ])->from(['elk_reasons_discrep'])
            ->where(['elk_reasons_discrep.id_reestr' => $id]);
        return $query->one(\Yii::$app->elk);
    }

    //статус мероприятий
    public static function getStepStatusEvents($id)
    {
        $query = Events::find()->select(['date_plan', 'date_fact'])->where(['id' => $id])->one();

        if ($query != NUll) {

        $today = new \DateTime();
        $today = $today->format('Y-m-d');
        $status = "Нет данных";

        if ($query->date_fact == NULL) {
            if (strtotime($query->date_plan) >= strtotime($today)) {
                $status = "В работе";
//                return $status;
            } elseif (strtotime($query->date_plan) < strtotime($today)) {
                $status = "Не выполнено";
//                return $status;
            }
        } else {
            if (strtotime($query->date_plan) >= strtotime($query->date_fact)) {
                $status = "Выполнено в срок";
//                return $status;
            } else {
                $status = "Просрочено";
//                return $status;
            }
        }
        return $status;

     }  else {
            $status = "";
            return $status;}
    }

}
