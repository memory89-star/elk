<?php
////namespace frontend\modules\elk\models;
////
////use Yii;
////use common\models\User;
////use frontend\modules\elk\models\Department;
////use common\modules\userProfile\models\UserProfile;
////use yii\helpers\ArrayHelper;
////
/////**
//// * This is the model class for table "elk_sp_department_data".
//// *
//// * @property int    $id ID
//// * @property int    $emp_department_id Выбор подразделения
//// * @property string $emp_department_code Подразделение
//// * @property string $emp_department_name Краткое наименование подразделения
//// * @property string $emp_department_full_name Полное наименование подразделения
//// * @property string $emp_department_type Тип подразделения
//// * @property int    $doc_num_max Номер документа
//// * @property string $block Блокировка
//// * @property string $manager Ответственный
//// */
////class DepartmentData extends \yii\db\ActiveRecord
////{
////    /**
////     * @inheritdoc
////     */
////    public static function tableName()
////    {
////        return 'elk_sp_department_data';
////    }
////
////    /**
////     * @return \yii\db\Connection the database connection used by this AR class.
////     */
////    public static function getDb()
////    {
////        return Yii::$app->get('elk');
////    }
////
////    /**
////     * @inheritdoc
////     */
////    public function rules()
////    {
////        return [
////            [['id','emp_department_id','emp_department_type', 'emp_department_code', 'emp_department_name', 'emp_department_full_name', 'manager'], 'required'],
////            [['emp_department_id', 'doc_num_max', 'manager'], 'integer'],
////            [['emp_department_id','emp_department_type', 'emp_department_code', 'emp_department_name', 'emp_department_full_name',
////                'created_at', 'updated_at','user_last', 'user_first', 'time_create', 'time_update','manager', 'doc_num_max' ], 'safe'],
////            [['emp_department_code', 'block'], 'string', 'max' => 100],
////            [['emp_department_name'], 'string', 'max' => 900],
////            [['emp_department_type'], 'string', 'max' => 50],
////            [['emp_department_full_name'], 'string', 'max' => 254],
////            [['user_last', 'user_first'], 'string', 'max' => 32],
////            [['emp_department_id'], 'unique', 'message' => 'Выбранное подразделение уже присутствует в справочнике "Общие данные по подразделению"'],
////        ];
////    }
////
////    /**
////     * @inheritdoc
////     */
////    public function attributeLabels()
////    {
////        return [
////            'id' => 'Ключ',
////            'emp_department_id' => 'Выбор подразделения',
////            'emp_department_code' => 'Код подразделения',
////            'emp_department_name' => 'Краткое наименование',
////            'emp_department_full_name' => 'Полное наименование',
////            'emp_department_type'  => 'Тип подразделения',
////            'doc_num_max' => 'Номер документа',
////            'manager' => 'Ответственный',
////            'block' => 'Блокировка',
////            'user_first' => 'Создано',
////            'created_at' => 'Дата создания',
////            'time_create' => 'Время создания',
////            'user_last' => 'Изменено',
////            'updated_at' => 'Дата редактирования',
////            'time_update' => 'Время редактирования',
////        ];
////    }
////
////    /**
////     * @return \yii\db\ActiveQuery
////     */
////    public function getDepartment()
////    {
////        return $this->hasOne(Department::getMainDepartments(), ['id' => 'emp_department_id']);
////    }
////    /**
////     * @return \yii\db\ActiveQuery
////     */
////    public function getUserCreate()
////    {
////        return $this->hasOne(UserProfile::className(), ['user_id' => 'user_first']);
////    }
////    /**
////     * @return \yii\db\ActiveQuery
////     */
////    public function getUserUpdate()
////    {
////        return $this->hasOne(UserProfile::className(), ['user_id' => 'user_last']);
////    }
////
////
////    private $_block = [
////        '0' => 'Нет',
////        '1' => 'Да',
////    ];
////
////    /**
////     * Функция получения статичных статусов блокировки
////     * @return array
////     */
////    public function block()
////    {
////        return $this->_block;
////    }
////
////    public function getShortCodeShortName()
////    {
////        return trim($this->emp_department_code.' '.$this->emp_department_name);
////    }
////
////    /**
////     * Функция получения списка подразделений
////     * @return array
////     */
////    public function getDepartmentList()
////    {
////        $query = Department::getMainDepartments();
////        return ArrayHelper::map($query, 'id' , function($one) {
////            return $one->code.' '.$one->name;
////        });
////    }
////
////    /**
////     * Функция возвращает описание статуса по входящему id
////     * @param string id
////     * @return string
////     */
////    public function setBlock($id)
////    {
////        return ($id == '0') ? 'Нет' : 'Да';
////    }
////
//////    /**
//////     * Функция возвращает ФИО пользователя по входящему payload
//////     * @param integer payload
//////     * @return string
//////     */
//////    public function getName($payload)
//////    {
//////        $query = UserProfile::find()->select(['secondname', 'firstname', 'thirdname'])->where(['user_id' => $payload])->one();
//////        return $query->secondname.' '.$query->firstname.' '.$query->thirdname;
//////    }
////
////    /**
////     * Функция возвращает ФИО пользователя по id
////     * @return string
////     */
////    public function getUserById()
////    {
////        $query = UserProfile::find()->select(['secondname', 'firstname', 'thirdname'])->where(['user_id' => Yii::$app->user->identity->id])->one();
////        return $query->secondname.' '.$query->firstname.' '.$query->thirdname;
////    }
////
//////    /**
//////     * Функция возвращает ФИО пользователя по id
//////     * @param integer $id
//////     */
//////    public function getUserById($id)
//////    {
//////        $query = UserProfile::find()->select(['secondname', 'firstname', 'thirdname'])->where(['user_id' =>  $id])->one();
//////        return $query->secondname.' '.$query->firstname.' '.$query->thirdname;
//////    }
////
////    /**
////     * Функция возвращает информацию по подразделению по входящему id
////     * @param integer emp_department_id
////     * @return array
////     */
////    public function getInformationDepartment($emp_department_id)
////    {
////        $query = Department::find()->select(['code', 'name', 'full_name'])->where(['id' => $emp_department_id])->one();
////        return [
////            'emp_department_code' => $query->code,
////            'emp_department_name' => $query->name,
////            'emp_department_full_name' => $query->full_name,
////        ];
////    }
////
////    /**
////     * Функция возвращает список сотрудников предприятия
////     * @return array|null|\yii\db\ActiveRecord
////     */
////    public function getUser()
////    {
////        $query = UserProfile::find()->orderBy('secondname ASC')->all();
////        return ArrayHelper::map($query, 'id', function($one) {
////            return $one->secondname.' '.$one->firstname.' '.$one->thirdname;
////        });
////    }
////
////    /**
////     * Функция возвращает список user_id состава комиссии по id подразделения(DepartmentData)
////     * @param integer id
////     * @return array
////     */
////    public static function getCommissionListByElkIdDepartment($id_department)
////    {
////        $query = DepartmentData::find()->select(['manager'])->where(['id' => $id_department])->all();
////        return ArrayHelper::getColumn(ArrayHelper::toArray($query),'manager');
////    }
////    public static function getCommissionListByElkIdDepartment1($id_department)
////    {
////        $query = DepartmentData::find()->select(['manager'])->where(['id' => $id_department])->all();
////        return ArrayHelper::getColumn(ArrayHelper::toArray($query),'manager');
////    }
////
////
////
////    public function getDepartmentData($id_department_kontrolled)
////    {
////        $query1 = DepartmentData::find()->select(['emp_department_id'])->where(['id' => $id_department_kontrolled])->one();
////        $query = \frontend\modules\elk\models\Department::find()->select(['code', 'name', 'full_name'])->where(['id' => $query1->emp_department_id])->one();
////        return [
////            'emp_department_code' => $query->code,
////            'emp_department_name' => $query->name,
////            'emp_department_full_name' => $query->full_name,
////        ];
////    }
////}
//
//
//namespace frontend\modules\elk\models;
//
//use Yii;
//use common\models\User;
//use frontend\modules\elk\models\Department;
//use common\modules\userProfile\models\UserProfile;
//use yii\helpers\ArrayHelper;
//
///**
// * This is the model class for table "elk_sp_department_data".
// *
// * @property int $id ID
// * @property int $emp_department_id Выбор подразделения
// * @property string $emp_department_code Подразделение
// * @property string $emp_department_name Краткое наименование подразделения
// * @property string $emp_department_full_name Полное наименование подразделения
// * @property string $emp_department_type Тип подразделения
// * @property int $doc_num_max Номер документа
// * @property string $block Блокировка
// * @property string $manager Ответственный
// */
//class DepartmentData extends \yii\db\ActiveRecord
//{
//    /**
//     * @inheritdoc
//     */
//    public static function tableName()
//    {
//        return 'elk_sp_department_data';
//    }
//
//    /**
//     * @return \yii\db\Connection the database connection used by this AR class.
//     */
//    public static function getDb()
//    {
//        return Yii::$app->get('elk');
//    }
//
//    /**
//     * @inheritdoc
//     */
//    public function rules()
//    {
//        return [
//            [['id', 'emp_department_id', 'emp_department_type', 'emp_department_code', 'emp_department_name', 'emp_department_full_name', 'manager'], 'required'],
//            [['emp_department_id', 'doc_num_max', 'manager'], 'integer'],
//            [['created_at', 'updated_at', 'user_last', 'user_first', 'time_create', 'time_update', 'manager'], 'safe'],
//            [['emp_department_code', 'block'], 'string', 'max' => 100],
//            [['emp_department_name'], 'string', 'max' => 900],
//            [['emp_department_type'], 'string', 'max' => 50],
//            [['emp_department_full_name'], 'string', 'max' => 254],
//            [['user_last', 'user_first'], 'string', 'max' => 32],
//            [['emp_department_id'], 'unique', 'message' => 'Выбранное подразделение уже присутствует в справочнике "Общие данные по подразделению"'],
//        ];
//    }
//
//    /**
//     * @inheritdoc
//     */
//    public function attributeLabels()
//    {
//        return [
//            'id' => 'Ключ',
//            'emp_department_id' => 'Выбор подразделения',
//            'emp_department_code' => 'Код подразделения',
//            'emp_department_name' => 'Краткое наименование',
//            'emp_department_full_name' => 'Полное наименование',
//            'emp_department_type' => 'Тип подразделения',
//            'doc_num_max' => 'Номер документа',
//            'manager' => 'Ответственный',
//            'block' => 'Блокировка',
//            'user_first' => 'Создано',
//            'created_at' => 'Дата создания',
//            'time_create' => 'Время создания',
//            'user_last' => 'Изменено',
//            'updated_at' => 'Дата редактирования',
//            'time_update' => 'Время редактирования',
//        ];
//    }
//
//    /**
//     * @return \yii\db\ActiveQuery
//     */
//    public function getDepartment()
//    {
//        return $this->hasOne(Department::getMainDepartments(), ['id' => 'emp_department_id']);
//    }
//
//    /**
//     * @return \yii\db\ActiveQuery
//     */
//    public function getUserCreate()
//    {
//        return $this->hasOne(UserProfile::className(), ['user_id' => 'user_first']);
//    }
//
//    /**
//     * @return \yii\db\ActiveQuery
//     */
//    public function getUserUpdate()
//    {
//        return $this->hasOne(UserProfile::className(), ['user_id' => 'user_last']);
//    }
//
//
//    private $_block = [
//        '0' => 'Нет',
//        '1' => 'Да',
//    ];
//
//    /**
//     * Функция получения статичных статусов блокировки
//     * @return array
//     */
//    public function block()
//    {
//        return $this->_block;
//    }
//
//    public function getShortCodeShortName()
//    {
//        return trim($this->emp_department_code . ' ' . $this->emp_department_name);
//    }
//
//    /**
//     * Функция получения списка подразделений
//     * @return array
//     */
//    public function getDepartmentList()
//    {
//        $query = Department::getMainDepartments();
//        return ArrayHelper::map($query, 'id', function ($one) {
//            return $one->code . ' ' . $one->name;
//        });
//    }
//
//    /**
//     * Функция возвращает описание статуса по входящему id
//     * @param string id
//     * @return string
//     */
//    public function setBlock($id)
//    {
//        return ($id == '0') ? 'Нет' : 'Да';
//    }
//
//    /**
//     * Функция возвращает ФИО пользователя по входящему payload
//     * @param integer payload
//     * @return string
//     */
//    public function getName($payload)
//    {
//        $query = UserProfile::find()->select(['secondname', 'firstname', 'thirdname'])->where(['user_id' => $payload])->one();
//        return $query->secondname . ' ' . $query->firstname . ' ' . $query->thirdname;
//    }
//
//    /**
//     * Функция возвращает ФИО пользователя по id
//     * @return string
//     */
//    public function getUserById()
//    {
//        $query = UserProfile::find()->select(['secondname', 'firstname', 'thirdname'])->where(['user_id' => Yii::$app->user->identity->id])->one();
//        return $query->secondname . ' ' . $query->firstname . ' ' . $query->thirdname;
//    }
//
//    /**
//     * Функция возвращает информацию по подразделению по входящему id
//     * @param integer emp_department_id
//     * @return array
//     */
//    public function getInformationDepartment($emp_department_id)
//    {
//        $query = Department::find()->select(['code', 'name', 'full_name'])->where(['id' => $emp_department_id])->one();
//        return [
//            'emp_department_code' => $query->code,
//            'emp_department_name' => $query->name,
//            'emp_department_full_name' => $query->full_name,
//        ];
//    }
//
//    /**
//     * Функция возвращает список сотрудников предприятия
//     * @return array|null|\yii\db\ActiveRecord
//     */
//    public function getUser()
//    {
//        $query = UserProfile::find()->orderBy('secondname ASC')->all();
//        return ArrayHelper::map($query, 'id', function ($one) {
//            return $one->secondname . ' ' . $one->firstname . ' ' . $one->thirdname;
//        });
//    }
//
//    /**
//     * Функция возвращает список user_id состава комиссии по id подразделения(DepartmentData)
//     * @param integer id
//     * @return array
//     */
//    public static function getCommissionListByElkIdDepartment($id_department_kontrolled)
//    {
////        $query = self::find()->select(['manager'])->where(['id' => $id_department_kontrolled])->all();
////        return ArrayHelper::getColumn(ArrayHelper::toArray($query),'manager');
//
//        $query = DepartmentData::find()->select(['manager'])->where(['id' => $id_department_kontrolled])->all();
////        $query1 = UserProfile::find()->select(['card_id'])->where(['id' => $query->manager])->one();
//        return ArrayHelper::getColumn(ArrayHelper::toArray($query), 'manager');
//    }
//
//
//    public function getDepartmentData()
//    {
//        $query = \frontend\modules\elk\models\Department::find()->select(['code', 'name', 'full_name'])->where(['id' => $this->emp_department_id])->one();
//        return [
//            'emp_department_code' => $query->code,
//            'emp_department_name' => $query->name,
//            'emp_department_full_name' => $query->full_name,
//        ];
//    }
//}
//


namespace frontend\modules\elk\models;

use Yii;
use common\models\User;
use frontend\modules\elk\models\Department;
use common\modules\userProfile\models\UserProfile;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "elk_sp_department_data".
 *
 * @property int $id ID
 * @property int $emp_department_id Выбор подразделения
 * @property string $emp_department_code Подразделение
 * @property string $emp_department_name Краткое наименование подразделения
 * @property string $emp_department_full_name Полное наименование подразделения
 * @property string $emp_department_type Тип подразделения
 * @property int $doc_num_max Номер документа
 * @property string $block Блокировка
 * @property string $manager Ответственный
 */
class DepartmentData extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'elk_sp_department_data';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('elk');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id','emp_department_id', 'doc_num_max', 'manager', 'user_last', 'user_first'], 'integer'],
            [['emp_department_id', 'emp_department_type', 'emp_department_code', 'emp_department_name', 'emp_department_full_name', 'manager'], 'required'],
            [['created_at', 'updated_at', 'user_last', 'user_first', 'time_create', 'time_update', 'manager'], 'safe'],
            [['emp_department_code', 'block'], 'string', 'max' => 100],
            [['emp_department_name'], 'string', 'max' => 900],
            [['emp_department_type'], 'string', 'max' => 50],
            [['emp_department_full_name'], 'string', 'max' => 254],
//            [['user_last', 'user_first'], 'string', 'max' => 32],
            [['emp_department_id'], 'unique', 'message' => 'Выбранное подразделение уже присутствует в справочнике "Общие данные по подразделению"'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Ключ',
            'emp_department_id' => 'Выбор подразделения',
            'emp_department_code' => 'Код подразделения',
            'emp_department_name' => 'Краткое наименование',
            'emp_department_full_name' => 'Полное наименование',
            'emp_department_type' => 'Тип подразделения',
            'doc_num_max' => 'Номер документа',
            'manager' => 'Ответственный',
            'block' => 'Блокировка',
            'user_first' => 'Создано',
            'created_at' => 'Дата создания',
            'time_create' => 'Время создания',
            'user_last' => 'Изменено',
            'updated_at' => 'Дата редактирования',
            'time_update' => 'Время редактирования',
            'active' => 'Действия',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDepartment()
    {
        return $this->hasOne(Department::getMainDepartments(), ['id' => 'emp_department_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserCreate()
    {
        return $this->hasOne(UserProfile::className(), ['user_id' => 'user_first']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserUpdate()
    {
        return $this->hasOne(UserProfile::className(), ['user_id' => 'user_last']);
    }


    private $_block = [
        '0' => 'Нет',
        '1' => 'Да',
    ];

    /**
     * Функция получения статичных статусов блокировки
     * @return array
     */
    public function block()
    {
        return $this->_block;
    }

    public function getShortCodeShortName()
    {
        return trim($this->emp_department_code . ' ' . $this->emp_department_name);
    }

    /**
     * Функция получения списка подразделений
     * @return array
     */
    public function getDepartmentList()
    {
        $query = Department::getMainDepartments();
        return ArrayHelper::map($query, 'id' , function($one) {
            return $one->code.' '.$one->name;
        });
    }

    /**
     * Функция возвращает описание статуса по входящему id
     * @param string id
     * @return string
     */
    public function setBlock($id)
    {
        return ($id == '0') ? 'Нет' : 'Да';
    }

    /**
     * Функция возвращает ФИО пользователя по входящему payload
     * @param integer payload
     * @return string
     */
    public function getName($payload)
    {
        $query = UserProfile::find()->select(['secondname', 'firstname', 'thirdname'])->where(['user_id' => $payload])->one();
        return $query->secondname . ' ' . $query->firstname . ' ' . $query->thirdname;
    }

    /**
     * Функция возвращает ФИО пользователя по id
     * @return string
     */
    public function getUserById($id)
    {
        $query = UserProfile::find()->select(['secondname', 'firstname', 'thirdname'])->where(['user_id' => $id])->one();
        return $query->secondname . ' ' . $query->firstname . ' ' . $query->thirdname;
    }

    /**
     * Функция возвращает информацию по подразделению по входящему id
     * @param integer emp_department_id
     * @return array
     */
    public function getInformationDepartment($emp_department_id)
    {
        $query = Department::find()->select(['code', 'name', 'full_name'])->where(['id' => $emp_department_id])->one();
        return [
            'emp_department_code' => $query->code,
            'emp_department_name' => $query->name,
            'emp_department_full_name' => $query->full_name,
        ];
    }

    /**
     * Функция возвращает список сотрудников предприятия
     * @return array|null|\yii\db\ActiveRecord
     */
    public function getUser()
    {
        $query = UserProfile::find()->orderBy('secondname ASC')->all();
        return ArrayHelper::map($query, 'id', function ($one) {
            return $one->secondname . ' ' . $one->firstname . ' ' . $one->thirdname;
        });
    }

    /**
     * Функция возвращает список user_id состава комиссии по id подразделения(DepartmentData)
     * @param integer id
     * @return array
     */
    public static function getCommissionListByElkIdDepartment($id_department_kontrolled)
    {
        $query = DepartmentData::find()->select(['manager'])->where(['id' => $id_department_kontrolled])->all();
        return ArrayHelper::getColumn(ArrayHelper::toArray($query), 'manager');
    }


    public function getDepartmentData()
    {
        $query = \frontend\modules\elk\models\Department::find()->select(['code', 'name', 'full_name'])->where(['id' => $this->emp_department_id])->one();
        return [
            'emp_department_code' => $query->code,
            'emp_department_name' => $query->name,
            'emp_department_full_name' => $query->full_name,
        ];
    }
}

