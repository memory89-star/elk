<?php

namespace backend\models;

use Yii;
use yii\db\ActiveRecord;
use common\modules\userProfile\models\UserProfile;
use common\models\User;

/**
 * This is the model class for table "scheduler_jobs".
 *
 * @property int $id
 * @property string $module
 * @property string $job_action
 * @property int $status
 * @property string $frequency
 * @property string $frequency_type
 * @property int $priority
 * @property int $is_notify
 * @property int $notify_user_id
 * @property string $description
 */
class SchedulerJobs extends ActiveRecord
{
    const IS_NOTIFY_NO = 0;
    const IS_NOTIFY_YES = 1;
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const FREQ_MINUTELY = 'minutely';
    const FREQ_HOURLY = 'hourly';
    const FREQ_DAILY = 'daily';
    const FREQ_WEEKLY = 'weekly';
    const FREQ_MONTHLY = 'monthly';
    const FREQ_DAYLY_IN_MORNING = 'daily_in_morning';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'scheduler_jobs';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status'], 'default', 'value' => null],
            [['status', 'notify_user_id', 'is_notify', 'priority'], 'integer'],
            [['module', 'frequency', 'frequency_type'], 'string', 'max' => 40],
            [['job_action', 'description'], 'string', 'max' => 245],
            [['module', 'frequency', 'frequency_type', 'job_action'], 'required'],
            ['status', 'in', 'range' => [self::STATUS_INACTIVE, self::STATUS_ACTIVE]],
            ['is_notify', 'in', 'range' => [self::IS_NOTIFY_NO, self::IS_NOTIFY_YES]],
            ['frequency_type', 'in', 'range' => [self::FREQ_MINUTELY, self::FREQ_HOURLY, self::FREQ_DAILY, self::FREQ_WEEKLY, self::FREQ_MONTHLY, self::FREQ_DAYLY_IN_MORNING]],

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'module' => Yii::t('app', 'Module'),
            'job_action' => Yii::t('app', 'Job Action'),
            'status' => Yii::t('app', 'Status'),
            'frequency_type' => Yii::t('app', 'Frequency Type'),
            'frequency' => Yii::t('app', 'Frequency'),
            'notify_user_id' => Yii::t('app', 'Notify User Id'),
            'is_notify' => Yii::t('app', 'Is Notify'),
            'priority' => Yii::t('app', 'Priority'),
            'description' => Yii::t('app', 'Description'),
        ];
    }

    public static function getAvailableJobs()
    {
        return SchedulerJobs::find()->andFilterWhere(['=', 'status', self::STATUS_ACTIVE])->orderBy(['priority' => SORT_DESC])->all();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserProfile()
    {
        return $this->hasOne(UserProfile::class, ['user_id' => 'notify_user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'notify_user_id']);
    }
}
