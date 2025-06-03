<?php

namespace frontend\modules\elk\models;

use common\modules\userProfile\models\UserProfile;
use Faker\Provider\cs_CZ\DateTime;
use Yii;
use yii\base\BaseObject;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;
use yii\db\Expression;
use yii\db\Query;


class Discrepancy extends \yii\db\ActiveRecord
{

    public string $status_ds = '';

    const SCENARIO_CREATE = 'scenario_create';
    const SCENARIO_UPDATE = 'scenario_update';


    public static function tableName()
    {
        return 'elk_reasons_discrep';
    }

    public static function getDb()
    {
        return Yii::$app->get('elk');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'id_reestr', 'user_last', 'user_first'], 'integer'],
            [['id_reestr','doc_num','discrepancy','created_at', 'updated_at',
                'user_last', 'user_first', 'time_create', 'time_update', 'status_ds'], 'safe'],
            [['id_reestr'], 'required'],
            [['discrepancy'], 'string', 'max' => 200],
//            [['user_last', 'user_first'], 'string', 'max' => 32],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'Ключ',
            'id_reestr' => 'Код таблицы Реестр ЛК',
            'doc_num' => '№ п/п',
            'discrepancy' => 'Причина несоответствия',
            'created_at' => 'timestamp not null',
            'updated_at' => 'timestamp not null',
            'user_last' => 'Последний пользователь',
            'user_first' => 'Создавший пользователь',
            'status_ds'  => 'Статус выполнения',
        ];
    }

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

    public function getReestrNesootv($id)
    {
//        $query = Discrepancy::find()->select(['id_reestr'])->where(['id' => $id])->one();
//        $query1 = Reestr::find()->select(['incongruity'])->where(['id' => $query->id_reestr])->one();
//        return $query1->incongruity;
    }

//    /**
//     * Функция возвращает ФИО пользователя по id
//     * @return string
//     */
//    public function getUserById()
//    {
//        $query = UserProfile::find()->select(['secondname', 'firstname', 'thirdname'])->where(['user_id' => Yii::$app->user->identity->id])->one();
//        return $query->secondname.' '.$query->firstname.' '.$query->thirdname;
//    }

    /**
     * Функция возвращает ФИО пользователя по id
     * @param integer $id
     */
    public function getUserById($id)
    {
        $query = UserProfile::find()->select(['secondname', 'firstname', 'thirdname'])->where(['user_id' =>  $id])->one();
        return $query->secondname.' '.$query->firstname.' '.$query->thirdname;
    }
    /**
     * Функция возвращает список сотрудников предприятия
     * @return array|null|\yii\db\ActiveRecord
     */
    public function getUser()
    {
        $query = UserProfile::find()->orderBy('secondname ASC')->all();
        return ArrayHelper::map($query, 'id', function($one) {
            return $one->secondname.' '.$one->firstname.' '.$one->thirdname;
        });
    }

    //        Выводим Код объекта ЛК на index
    public function getKodInd($id)
    {
        $query1 = Reestr::find()->select(['id_objects'])->where(['id' => $id])->one();
        $query2 = Kod::find()->select(['kod_objects','name'])->where(['id' => $query1->id_objects])->one();
        return $query2->kod_objects. ' '.$query2->name;
    }

    //Статус на странице Index
    public static function getStatus($id)
    {
//        $query = Events::find()->select(['id'])
//                               ->where(['id_discrepancy' => $id])
////                               ->andWhere(['date_fact' == NULL])
//                               ->all();
        $status = 0;
        $query = (new Query())
            ->select([
                'count(elk_events.id) as kol',
            ])
            ->from(['elk_events'])
            ->where(['elk_events.id_discrepancy' => $id])
            ->andWhere(['elk_events.date_fact' => NULL]);

        $rez = $query->one(\Yii::$app->elk);

        if ($rez['kol'] == NULL) {
            return $status = 3;
        }

        if ($rez['kol'] == 0) {
            return $status = 2;
        } else {
            return $status = 1;
        }

    }

    public function getDepartmentId($id)
    {
        $query = Reestr::find()->select(['id_department_kontrolled'])->where(['id' => $id])->one();
        $query1 = DepartmentData::find()->select(['emp_department_id','emp_department_type'])->where(['id' => $query->id_department_kontrolled])->one();
        return ($query1->emp_department_type === 'Контролируемое') ? $query1->emp_department_id:NULL;
    }


  }





