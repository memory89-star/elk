<?php

namespace frontend\modules\elk\models;

use common\modules\userProfile\models\UserProfile;
use Faker\Provider\cs_CZ\DateTime;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;
use yii\db\Expression;


class Events extends \yii\db\ActiveRecord
{

        public string $status_ev = '';

    const SCENARIO_CREATE = 'scenario_create';
    const SCENARIO_UPDATE = 'scenario_update';

    const DEADLINE_NOT_COME = ['id'=>1, 'desc' => 'В работе','color'=>'black'];
    const DEADLINE_PASS = ['id'=>2, 'desc' => 'Не выполнено','color'=>'red'];
    const TIME_DIVIATION = ['id'=>3, 'desc' => 'Просрочено','color'=>'orange'];
    const STEP_DONE = ['id'=>4, 'desc' => 'Выполнено в срок','color'=>'green'];


    public static function tableName()
    {
        return 'elk_events';
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
            [['id', 'id_reestr','id_discrepancy','doc_num', 'id_otvetst', 'id_kontrol', 'user_last', 'user_first'], 'integer'],
            [['id_reestr','id_discrepancy','events','doc_num', 'date_fact',
                'created_at', 'updated_at','date_plan', 'id_kontrol',
                'user_last', 'user_first', 'time_create', 'time_update','id_otvetst', 'status_ev' ], 'safe'],
            [['id_discrepancy','events', 'id_otvetst', 'id_kontrol', 'date_plan'], 'required'],
            [['events'], 'string', 'max' => 200],
//            [['user_last', 'user_first'], 'string', 'max' => 32],
//            ['date_fact', 'compare', 'compareAttribute' => 'date_plan', 'operator' => '>=', 'message' => 'Фактическая дата должна быть больше либо равна плановой дате.'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id_reestr' => 'Код таблицы Реестр ЛК',
            'id_discrepancy' => 'Код таблицы Причины несоответствия',
            'doc_num' => '№ п/п',
            'events' => 'Описание мероприятия',
            'id_otvetst' => 'Ответственный',
            'id_kontrol' => 'Контролирующий',
            'date_plan' => 'Плановый срок',
            'date_fact' => 'Фактическая дата',
            'created_at' => 'timestamp not null',
            'updated_at' => 'timestamp not null',
            'user_last' => 'Последний пользователь',
            'user_first' => 'Создавший пользователь',
            'status_ev' => 'Статус выполнения',
            'active' => 'Действия',
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

    //Сохранение в Events  №**********************************************************
    public function init()
    {
        parent::init();
        $this->on(self::EVENT_AFTER_INSERT, [$this, 'fillInsert']);
    }

    public function fillInsert()
    {
        self::fillIDEvents();

    }

    public function fillIDEvents()
    {
        if ($this->id_discrepancy && $this->id) {
            if (Events::getIdDiscrepancy($this->id_discrepancy) == NULL) {
                $events = Events::find()->where(['id_discrepancy' => $this->id_discrepancy])->one();
                $this->id_discrepancy = $events->id_discrepancy;
                $events->doc_num = 1;
                $events->id_reestr = Events::getIDReestr($this->id_discrepancy);
                $events->save(false);
            } else {
                $events = Events::find()->where(['id'=>$this->id])->one();
                $this->id_discrepancy = $events->id_discrepancy;
                $events->id_reestr = Events::getIDReestr($this->id_discrepancy);
                $events->doc_num = Events::getIdMax($this->id_discrepancy);
                $events->save(false);
            }
        }
    }

        public function getIdDiscrepancy($id_discrepancy)
    {
        $query = Events::find()->select(['id'])->where(['id_discrepancy'=>$id_discrepancy])->all();
        return ArrayHelper::getColumn($query, 'id');
    }
    public function getIdMax($id_discrepancy)
    {
        $query = Events::find()->select(['max(doc_num) as doc_num'])->where(['id_discrepancy'=>$id_discrepancy])->one();
        return ($query->doc_num)+1;
    }

        //Функция генерации порядкового номера
        public function getIDReestr($id)
    {
        $query = Discrepancy::find()->select(['id_reestr'])->where(['id' => $id])->one();
        return $query->id_reestr;
    }

    //**************************************************************************************************


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

    //возвращаемся назад с Мероприятия к Причинам несоответствия
    public function getDiscrID($id)
    {
        $query = Discrepancy::find()->select(['id_reestr'])->where(['id' => $id])->one();
        return $query->id_reestr;
    }

    //        Выводим Предложение на index
    public function getPredl($id)
    {
        $query = Events::find()->select(['id_discrepancy'])->where(['id' => $id])->one();
        $query1 = Discrepancy::find()->select(['discrepancy'])->where(['id' => $query->id_discrepancy])->one();
        return $query1->discrepancy;
    }

//    public function getOtvetst($id)
//    {
//        $query = DepartmentData::find()->select(['emp_department_code'])->where(['id' => $id])->one();
//        return $query->emp_department_code;
//    }

    //        Выводим Код объекта ЛК на index
    public function getKodInd($id)
    {
        $query = Discrepancy::find()->select(['id_reestr'])->where(['id' => $id])->one();
        $query1 = Reestr::find()->select(['id_objects'])->where(['id' => $query->id_reestr])->one();
        $query2 = Kod::find()->select(['kod_objects','name'])->where(['id' => $query1->id_objects])->one();
        return $query2->kod_objects. ' '.$query2->name;
    }
    //        Выводим Несоответствие на index
    public function getReestrInd($id)
    {
        $query = Discrepancy::find()->select(['id_reestr'])->where(['id' => $id])->one();
        $query1 = Reestr::find()->select(['incongruity'])->where(['id' => $query->id_reestr])->one();
        return $query1->incongruity;
    }

    /**
     * Функция возвращает ответственного в Index
     */
    public function getUserOtv($id)
    {
        $query = UserProfile::find()->select(['secondname', 'firstname', 'thirdname'])->where(['id' => $id])->one();
        return $query->secondname.' '.$query->firstname.' '.$query->thirdname;
    }

    public static function getStepStatus($id)
    {
        $query = Events::find()->select(['date_plan', 'date_fact'])->where(['id' => $id])->one();
        $today = new \DateTime();
        $today = $today->format('Y-m-d');
        $status = 0;
        if($query->date_fact == NULL){
            if(strtotime($query->date_plan) >= strtotime($today)){
                $status = 1;
            }elseif(strtotime($query->date_plan) < strtotime($today)){
                $status = 2;
            }
        }else{
            if(strtotime($query->date_plan) >= strtotime($query->date_fact)){
                $status = 4;
            }else{
                $status = 3;
            }
        }
        return $status;


//        $query = Events::find()->select(['date_plan', 'date_fact'])->where(['id' => $id])->one();
//
//        $today = new \DateTime();
//        $today = $today->format('Y-m-d');
//        $status = 0;
//        if($query->date_fact == NULL) {
//
//            if(strtotime($query->date_plan) >= strtotime($today)){
//                $status = 1;
//            }elseif(strtotime($query->date_plan) < strtotime($today)){
//                $status = 2;
//            }
//        }else{
//            if(strtotime($query->date_plan) >= strtotime($query->date_fact)){
//                $status = 4;
//            }else{
//                $status = 3;
//            }
//        }
//        return $status;

//        $today = new \DateTime();
//        $today = $today->format('Y-m-d');
//        $status = 0;
//        if ($query->date_plan != NULL) {
//
//            if($query->date_fact == NULL){
//                if(strtotime($query->date_plan) >= strtotime($today)){
//                    $status = 1;
//                }elseif(strtotime($query->date_plan) < strtotime($today)){
//                    $status = 2;
//                }
//            }else{
//                if(strtotime($query->date_plan) >= strtotime($query->date_fact)){
//                    $status = 4;
//                }else{
//                    $status = 3;
//                }
//            }
//            return $status;
//        } else  return 5;

//
//        $query = Reestr::find()->select(['date_plan', 'date_fact'])->where(['id' => $id])->one();
//
//        $today = new \DateTime();
//        $today = $today->format('Y-m-d');
//        $status = 0;
//        if($query->date_fact == NULL) {
//
//            if(strtotime($query->date_plan) >= strtotime($today)){
//                $status = 1;
//            }elseif(strtotime($query->date_plan) < strtotime($today)){
//                $status = 2;
//            }
//        }else{
//            if(strtotime($query->date_plan) >= strtotime($query->date_fact)){
//                $status = 4;
//            }else{
//                $status = 3;
//            }
//        }
//        return $status;
    }

    public function getDepartmentId($id)
    {
        $query =  Discrepancy::find()->select(['id_reestr'])->where(['id' => $id])->one();
        $query1 = Reestr::find()->select(['id_department_kontrolled'])->where(['id' => $query->id_reestr])->one();
        $query2 = DepartmentData::find()->select(['emp_department_id','emp_department_type'])->where(['id' => $query1->id_department_kontrolled])->one();
        return ($query2->emp_department_type === 'Контролируемое') ? $query2->emp_department_id:NULL;
    }

    public static function getStepStatusExecute($date_plan, $date_fact)
    {
        $today = new \DateTime();
        $today = $today->format('Y-m-d');
        $status = 0;
        if($date_fact == NULL){
            if(strtotime($date_plan) >= strtotime($today)){
                $status = self::DEADLINE_NOT_COME;
            }elseif(strtotime($date_plan) < strtotime($today)){
                $status = self::DEADLINE_PASS;
            }
        }else{
            if(strtotime($date_plan) >= strtotime($date_fact)){
                $status = self::STEP_DONE;
            }else{
                $status = self::TIME_DIVIATION;
            }
        }
        return $status;
    }

    public function getReestrData($id)
    {
        $query1 = Events::find()->select(['id_reestr'])->where(['id' => $id])->one();
        $query = Reestr::find()->select(['identification_document_number', 'incongruity'])->where(['id' => $query1->id_reestr])->one();
        return [
            'identification_document_number' => $query->identification_document_number,
            'incongruity' => $query->incongruity,
        ];
    }

  }





