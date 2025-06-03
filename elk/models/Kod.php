<?php

namespace frontend\modules\elk\models;

use common\modules\userProfile\models\UserProfile;
use Faker\Provider\cs_CZ\DateTime;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;
use yii\db\Expression;


class Kod extends \yii\db\ActiveRecord
{

    const SCENARIO_CREATE = 'scenario_create';
    const SCENARIO_UPDATE = 'scenario_update';


    public static function tableName()
    {
        return 'elk_sp_kod';
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
            [['id', 'kod_objects', 'user_last', 'user_first'], 'integer'],
            [['kod_objects', 'name', 'block', 'created_at', 'updated_at',
                'user_last', 'user_first', 'time_create', 'time_update', ], 'safe'],
            [['kod_objects', 'name',], 'required'],
            [['name'], 'string', 'max' => 200],
//            [['user_last', 'user_first'], 'string', 'max' => 32],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'kod_objects' => 'Код объекта',
            'name' => 'Описание',
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

  }





