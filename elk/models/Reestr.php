<?php

namespace frontend\modules\elk\models;

use common\modules\userProfile\models\UserProfile;
use frontend\modules\elk\models\helpers\SendMailElk;
use frontend\modules\elk\models\Step;
use frontend\modules\elk\models\DepartmentData;
use frontend\modules\elk\models\helpers\MailHelper;
use yii\db\Query;
use DateTime;
use Yii;
use yii\base\BaseObject;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;
use yii\db\Expression;

class Reestr extends \yii\db\ActiveRecord
{
//    public string $date_y = '';
    public $comment_omep = '';

    const SCENARIO_CREATE = 'scenario_create';
    const SCENARIO_UPDATE = 'scenario_update';

    const DEADLINE_NOT_COME = ['id'=>1, 'desc' => 'В работе','color'=>'black'];
    const DEADLINE_PASS = ['id'=>2, 'desc' => 'Не выполнено','color'=>'red'];
    const TIME_DIVIATION = ['id'=>3, 'desc' => 'Просрочено','color'=>'orange'];
    const STEP_DONE = ['id'=>4, 'desc' => 'Выполнено в срок','color'=>'green'];


    public static function tableName()
    {
        return 'elk_reestr';
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
            [['id', 'id_department_kontrolling','id_department_kontrolled','id_objects','id_significance','id_step','id_otvetst','id_kontrol','user_last', 'user_first'], 'integer'],
            [['date_registr','date_detection','opisan','id_department_kontrolling',
                'id_department_kontrolled','id_objects','id_significance','id_step', 'date_fact',
                'id_otvetst','id_kontrol','created_at', 'updated_at','identification_document_number',
                'incongruity', 'requirements_not_met', 'reason_modification','events_elimination','date_plan',
                'user_last', 'user_first', 'time_create', 'time_update', 'year', 'month', 'status', 'manager'], 'safe'],
            [['date_detection', 'id_department_kontrolling', 'id_department_kontrolled','id_objects','id_significance','manager', 'incongruity', 'requirements_not_met'], 'required'],
            [['opisan'], 'string', 'max' => 200],
            [['year'], 'string', 'max' => 4],
            [['month'], 'string', 'max' => 12],
            [['identification_document_number'], 'string', 'max' => 20],
            [['incongruity'], 'string', 'max' => 200],
            [['requirements_not_met'], 'string', 'max' => 200],
            [['reason_modification'], 'string', 'max' => 200],
            [['events_elimination'], 'string', 'max' => 200],
//            [['user_last', 'user_first'], 'string', 'max' => 32],
//            ['date_fact', 'compare', 'compareAttribute' => 'date_plan', 'operator' => '>=', 'message' => 'Фактическая дата должна быть больше либо равна плановой дате.'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id_department_kontrolling' => 'Контролирующее подразделение',
            'id_department_kontrolled' => 'Контролируемое подразделение',
            'id_objects' => 'Код объекта',
            'opisan' => 'Описание объекта ЛК',
            'id_significance' => 'Значимость',
            'id_step' => 'Этап реализации',
            'id_otvetst' => 'Ответственный',
            'id_kontrol' => 'Контролирующий',
            'date_detection' => 'Дата выявления',
            'date_registr' => 'Дата регистрации',
            'identification_document_number' => 'Рег. №',
            'incongruity' => 'Несоответствие',
            'requirements_not_met' => 'Не выполнены требования',
            'reason_modification' => 'Причина доработки',
            'events_elimination' => 'Мероприятия по устранению несоответствий',
            'manager' => 'Сопровождающий',
            'date_plan' => 'Плановый срок',
            'date_fact' => 'Фактическая дата',
            'created_at' => 'timestamp not null',
            'updated_at' => 'timestamp not null',
            'user_last' => 'Последний пользователь',
            'user_first' => 'Создавший пользователь',
            'year' => 'Год',
            'month' => 'Месяц',
            'status' => 'Статус',
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDepartmentData()
    {
        return $this->hasOne(DepartmentData::className(), ['id' => 'id_department_kontrolling']);
    }


    public function init()
    {
        parent::init();
        $this->on(self::EVENT_AFTER_INSERT, [$this, 'fillInsert']);
        $this->on(self::EVENT_BEFORE_UPDATE, [$this, 'doBeforeUpdate']);
//        $this->on(self::EVENT_AFTER_INSERT, [$this, 'doAfterInsert']);
    }
    //Сохранение в DepartmentDate мах №**********************************************************
    public function fillInsert()
    {
        self::fillIDDepartm();
    }

    public function fillIDDepartm()
    {
        if ($this->id_department_kontrolled){
                $department = DepartmentData::find()->where(['id'=>$this->id_department_kontrolled])->one();
                $this->id_department_kontrolled = $department->id;
                $department->doc_num_max = $this->identification_document_number;
                $department->save(false);
        }
    }
    //**************************************************************************************************

    //Проверка была ли проставлена Фактическая дата **********************************************************
    public function doBeforeUpdate()
    {
        if (is_null($this['oldAttributes']['date_fact'])&&($this->date_fact != null)) {
            $now = new \DateTime('today');
            $this->date_fact = $now->format('Y-m-d');
            SendMailElk::newElkMail($this,SendMailElk::MAIL_NEW_CLOSE);
        }
    }
    //**************************************************************************************************
//    //Отправить письмо при сохранении нового реестра **********************************************************
//    public function doAfterInsert()
//    {
//        self::sendMail();
//        if ($this->comment_omep){
//            self::sendNotificationOmep($this->comment_omep);
//            $this->comment_omep = '';
//        }
//    }
//
//    public function sendMail()
//    {
//        if ($this->manager){
//            $profiles = UserProfile::find()->where(['user_id' => $this->manager])->all();
//            if ($profiles) {
//                foreach ($profiles as $profile){
//                    $user = UserProfile::find()->where(['id'=>$profile->user_id])->one();
//                    if ($user) {
//                        MailHelper::prepareMail(MailHelper::MAIL_NEW_ELK,$this, $user, 'сопровождающий');
//                    }
//                }
//            }
//        }
//    }

    //**************************************************************************************************

    /**
     * Функция возвращает этап реализации по статусу
     * @param string $status
     * @return string
     */
    public function getStepByStatus($status)
    {
        $query = Step::find()->select(['id'])->where([$status => true])->andWhere(['=' ,'block', '0'])->one();
        return $query->id;
    }


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
     * Функция возвращает № регистрации
     * @return string
     */
    public function getDateReg()
    {
        $query = UserProfile::find()->select(['secondname', 'firstname', 'thirdname'])->where(['user_id' => Yii::$app->user->identity->id])->one();
        return $query->secondname.' '.$query->firstname.' '.$query->thirdname;
    }


    /**
     * Функция возвращает дату и номер
     * @return array
     */
    public function getInformationDate($id_department_kontrolled)
    {
        $identification_document_number = DepartmentData::find()->select(['doc_num_max'])->where(['id' => $id_department_kontrolled])->one();
        $identification_document_number = $identification_document_number->doc_num_max + 1;
        return [
            'identification_document_number' => $identification_document_number
        ];
    }

    /**
     * Функция возвращает описание объекта ЛК из справочника Код объекта
     * @return array
     */
    public function getInformationKod($id_objects)
    {
        $opisan = Kod::find()->select(['name'])->where(['id' => $id_objects])->one();
        return [
            'opisan' => $opisan->name
        ];
    }

    /**
     * Функция получения списка подразделений
     * @return array
     */
    public function getDepartmentListKontrl()
    {
        $query = DepartmentData::find()->orderBy('emp_department_code ASC')->where(['=' ,'block', '0'])->andWhere(['emp_department_type' => 'Контролирующее'])->all();
        return  ArrayHelper::map($query, 'id', function($one) {
            return $one->emp_department_code.' '.$one->emp_department_name;
        });
    }

    public function getDepartmentListKontr()
    {
        $query = DepartmentData::find()->orderBy('emp_department_code ASC')->where(['=' ,'block', '0'])->andWhere(['emp_department_type' => 'Контролируемое'])->all();
        return  ArrayHelper::map($query, 'id', function($one) {
            return $one->emp_department_code.' '.$one->emp_department_name;
        });
    }

    /**
     * Функция возвращает № регистрации
     * @return string
     */
    public function getKodOb()
    {
        $query = Kod::find()->where(['=' ,'block', '0'])->orderBy('kod_objects ASC')->all();
        return  ArrayHelper::map($query, 'id', function($one) {
            return $one->kod_objects;
        });
    }

    public function getKodObALL()
    {
        $query = Kod::find()->orderBy('kod_objects ASC')->where(['=' ,'block', '0'])->all();
        return  ArrayHelper::map($query, 'id', function($one) {
            return $one->kod_objects;
        });
    }

    /**
     * Функция возвращает Значимость несоответствия
     * @return string
     */
    public function getKodZn()
    {
        $query = Significance::find()->where(['=' ,'block', '0'])->orderBy('name ASC')->all();
        return  ArrayHelper::map($query, 'id', function($one) {
            return $one->name;
        });
    }

    public function getKodZnALL()
    {
        $query = Significance::find()->orderBy('name ASC')->where(['=' ,'block', '0'])->all();
        return  ArrayHelper::map($query, 'id', function($one) {
            return $one->name;
        });
    }

    /**
     * Функция возвращает Этап реализации
     * @return string
     */
    public function getStep()
    {
        $query = Step::find()->where(['=' ,'block', '0'])->orderBy('name ASC')->all();
        return  ArrayHelper::map($query, 'id', function($one) {
            return $one->name;
        });
    }

    /**
     * Функция возвращает Этап реализации по входящему id
     * @param integer $id
     * @return string
     */
    public function getNameStep($id)
    {
        $query = Step::find()->where(['id' => $id])->one();
        return $query->name;
    }

    public function getDepartmentKontr($id)
    {
        $query = DepartmentData::find()->select(['emp_department_code'])->where(['id' => $id])->one();
        return $query->emp_department_code;
    }

    public function getDepartmentId($id)
    {
        $query = DepartmentData::find()->select(['emp_department_id'])->where(['id' => $id])->one();
        return $query->emp_department_id;
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



    public function getUserName($id)
    {
        $query = UserProfile::find()->select(['secondname','firstname','thirdname'])->where(['id' => $id])->one();
        if ($query != NULL) {
            return $query->secondname.' '.$query->firstname.' '.$query->thirdname;
        } else NULL;

    }

    /**
     * Функция получения списка подразделений по id
     * @param integer $id
     * @return string
     */
    public function getDepartmentById($id)
    {
        $query = DepartmentData::find()->orderBy('emp_department_code ASC')->where(['id' => $id])->all();
        return  ArrayHelper::map($query, 'id', function($one) {
            return $one->emp_department_code.' '.$one->emp_department_name;
        });
    }

    /**
     * Функция получения списка подразделений
     * @return string
     */
    public function getDepartment()
    {
        $query = DepartmentData::find()->orderBy('emp_department_code ASC')->where(['=' ,'block', '0'])->andWhere(['emp_department_type' => 'Контролирующее'])->all();
        return  ArrayHelper::map($query, 'id', function($one) {
            return $one->emp_department_code.' '.$one->emp_department_name;
        });
    }

    /**
     * Функция получения списка подразделений
     * @return string
     */
//    public function getDepartment()
//    {
//        $query = DepartmentData::find()->orderBy('emp_department_code ASC')->where(['=' ,'block', '0'])->andWhere(['emp_department_type' => 'Контролируемое'])->all();
//        return  ArrayHelper::map($query, 'id', function($one) {
//            return $one->emp_department_code.' '.$one->emp_department_name;
//        });
//    }



    public function getStepStatusByStepId()
    {
        $status_desc = '';
        switch ($this->id_step){
            case 1: $status_desc = Step::getStepStatusExecute($this->start_plan,$this->start_fact)['desc'];
                break;
        }
        return $status_desc;
    }

    public static function getStepStatus($id)
    {
        $query = Reestr::find()->select(['date_plan', 'date_fact'])->where(['id' => $id])->one();

//        $status = 0;
//        if ($query->date_plan != NULL) {
//
//            $today = new \DateTime();
//            $today = $today->format('Y-m-d');
//            if($query->date_fact == NULL){
//                if(strtotime($query->date_plan) >= strtotime($today)){
//                    $status = 1;
//                }elseif(strtotime($query->date_plan) < strtotime($today)){
//                    $status = 2;
//                }
//            }else{
//                if(strtotime($query->date_plan) >= strtotime($query->date_fact)){
//                    $status = 4;
//                }elseif (strtotime($query->date_plan) < strtotime($query->date_fact)){
//                    $status = 3;
//                }
//            }
//            return $status;
//        } else  return 5;


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

    /**
     * Функция проверяет принадлежность пользователя к подразделению Контролирующее
     * @param integer $id
     * @return boolean
     */
    public function userDepartmentId($id)
    {
        $query = UserProfile::find()->select(['main_department_id'])->where(['user_id' => $id])->one();
        $department_id = DepartmentData::find()->select(['emp_department_type'])->where(['emp_department_id' => $query->main_department_id])->one();
        return $department_id->emp_department_type === 'Контролирующее' ? true : false;
    }

    /**
     * Функция проверяет принадлежность пользователя к подразделению Контролируемое
     * @param integer $id
     * @return boolean
     */

    public function userDepartmentIdK($id)
    {
        $query = UserProfile::find()->select(['main_department_id'])->where(['user_id' => $id])->one();
        $department_id = DepartmentData::find()->select(['emp_department_type'])->where(['emp_department_id' => $query->main_department_id])->one();
        return $department_id->emp_department_type === 'Контролируемое' ? true : false;
//        var_dump($id);
//        $risk_department_id = DepartmentData::find()->select(['emp_department_id'])->where(['id' => $id])->one();
//        $query = UserProfile::find()->select(['main_department_id'])->where(['user_id' => Yii::$app->user->identity->id])->one();
//        return $risk_department_id->emp_department_id === $query->main_department_id ? true : false;
    }

    /********************************************************************************************************************/




  }





