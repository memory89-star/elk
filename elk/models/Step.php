<?php

namespace frontend\modules\elk\models;

use common\modules\userProfile\models\UserProfile;
use Faker\Provider\cs_CZ\DateTime;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;
use yii\db\Expression;


class Step extends \yii\db\ActiveRecord
{
    const DEADLINE_NOT_COME = ['id'=>1, 'desc' => 'В работе','color'=>'black'];
    const DEADLINE_PASS = ['id'=>2, 'desc' => 'Не выполнено','color'=>'red'];
    const TIME_DIVIATION = ['id'=>3, 'desc' => 'Просрочено','color'=>'orange'];
    const STEP_DONE = ['id'=>4, 'desc' => 'Выполнено в срок','color'=>'green'];


    public static function tableName()
    {
        return 'elk_sp_step';
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
            [['id', 'index_namber', 'user_last', 'user_first'], 'integer'],
            [['index_namber', 'name', 'block', 'created_at', 'updated_at',
                'user_last', 'user_first', 'time_create', 'time_update', ], 'safe'],
            [['name',], 'required'],
            [['name'], 'string', 'max' => 200],
//            [['user_last', 'user_first'], 'string', 'max' => 32],
            [['v_for_creating_doc', 'v_after_accept_event'],'boolean'],

//            [[ 'v_for_creating_doc'], 'unique', 'targetAttribute'=>['v_for_creating_doc'=>false], 'message' => 'Признак уже установлен для другой записи'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'index_namber' => '№ п/п',
            'name' => 'Описание этапа',
            'v_for_creating_doc' => 'Установить при создании документа',
            'v_after_accept_event' => 'Установить после заполнения Мероприятия (блок "Коррекции")',
            'block' => 'Блокировка',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата изменения',
            'user_last' => 'Последний пользователь',
            'user_first' => 'Создавший пользователь',
            'time_create' => 'Время создания',
            'time_update' => 'Время редактирования',
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

    public function block()
    {
        return [
            '0'=>'Нет', //(по умолчанию)
            '1'=>'Да',
        ];
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

//    const DEADLINE_NOT_COME = ['id'=>1, 'desc' => 'В работе','color'=>'black'];
//    const DEADLINE_PASS = ['id'=>2, 'desc' => 'Не выполнено','color'=>'red'];
//    const TIME_DIVIATION = ['id'=>3, 'desc' => 'Просрочено','color'=>'orange'];
//    const STEP_DONE = ['id'=>4, 'desc' => 'Выполнено в срок','color'=>'green'];

  }





