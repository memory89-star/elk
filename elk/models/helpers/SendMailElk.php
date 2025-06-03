<?php


namespace frontend\modules\elk\models\helpers;

use common\helpers\MailHelper;
use common\models\User;

use frontend\modules\elk\models\DepartmentData;
use frontend\modules\elk\models\AuthAssignment;
use frontend\modules\elk\models\UserProfile;
use yii\helpers\ArrayHelper;


class SendMailElk
{
    const MAIL_NEW_ELK = ['fileName' => 'elkNotification.php',
        'subject' => 'АС «ЭЛК». Зарегистрировано новое несоответствие',
        'text' => 'Зарегистрирован новый документ по несоответствию в АС «ЭЛК».',
        'recipients' => 'getNewElkMailRecipient'];

    const MAIL_NEW_MEROPR = ['fileName' => 'elkNotificationMeropr.php',
        'subject' => 'АС «ЭЛК». Вы назначены ответственным/контролирующим по несоответствию.',
        'text' => 'Вы назначены ответственным/контролирующим по мероприятию:',
        'recipients' => 'getNewElkMailRecipientMeropr'];

    const MAIL_NEW_EVENTS = ['fileName' => 'elkNotificationEvents.php',
        'subject' => 'АС «ЭЛК». Вы назначены ответственным/контролирующим по корректирующим действиям.',
        'text' => 'Вы назначены ответственным/контролирующим по мероприятию :',
        'recipients' => 'getNewElkMailRecipientEvents'];

    const MAIL_NEW_CLOSE = ['fileName' => 'elkNotificationMeroprClose.php',
        'subject' => 'АС «ЭЛК». Закрыт документ по несоответствию.',
        'text' => 'Закрыт документ по несоответствию в АС «ЭЛК».',
        'recipients' => 'getNewElkMailRecipientClose'];

    //------------------    Автоматическое оповещение (Ночное задание)   ------------------
    const MAIL_ATTENTION_ACTION = ['fileName' => 'elkActionNotification.php',
        'subject' => 'АС «ЭЛК». Подходит срок исполнения мероприятия по устранению несоответствия.',
        'text' => 'Осталось ',
        'recipients' => 'getAttantionActionRecipient'];

    const MAIL_DELAY_ACTION = ['fileName' => 'riskActionNotification.php',
        'subject' => 'АС «ЭЛК». Истек срок исполнения мероприятия по устранению несоответствия',
        'text' => 'Срок исполнения мероприятий по устранению несоответствий истек ',
        'recipients' => 'getDelayActionRecipient'];

    const MAIL_ATTENTION_ACTION_EVENTS = ['fileName' => 'riskActionNotification.php',
        'subject' => 'АС «ЭЛК». Подходит срок исполнения мероприятия по устранению причины несоответствия',
        'text' => 'Осталось ',
        'recipients' => 'getAttantionActionEventsRecipient'];

    const MAIL_DELAY_ACTION_EVENTS = ['fileName' => 'riskActionNotification.php',
        'subject' => 'АС «ЭЛК». Истек срок исполнения мероприятия по устранению причины несоответствия',
        'text' => 'Срок исполнения мероприятий по устранению причины несоответствия истек ',
        'recipients' => 'getDelayActionEventsRecipient'];

    public static function  newElkMail($model, $typeMail,$addition_text = null)
    {
        $func = $typeMail['recipients'];
        $recipients = (self::$func($model));
        self::sendMail($model,$recipients,$typeMail, $addition_text);
    }

    public static function getNewElkMailRecipient($model)
    {
        $key_users = AuthAssignment::getUserListByRole('elk_user_gxk');
        foreach ($key_users as $key_user)
        {
            $recipients[] = UserProfile::getProfileByUserId($key_user)->card_id;
        }

        $idmanager  = DepartmentData::getCommissionListByElkIdDepartment($model->id_department_kontrolled);
        foreach ($idmanager as $manager)
        {
            $recipients[] = UserProfile::getProfileByCardId($manager)->card_id;
        }

        $recipients[] = $model->manager;

//        $recipients = array_merge($recipients1,$recipients2,$managermodel);
        return $recipients;
    }

    public static function getNewElkMailRecipientMeropr($model)
    {
        $recipients[] = $model->id_otvetst;
        $recipients[] = $model->id_kontrol;

//        $recipients = array_merge($recipients1,$recipients2,$managermodel);
        return $recipients;
    }

    public static function getNewElkMailRecipientEvents($model)
    {
        $recipients[] = $model->id_otvetst;
        $recipients[] = $model->id_kontrol;

        return $recipients;
    }

    public static function getNewElkMailRecipientClose($model)
    {
        //Работник имеющий роль Ответственный пользователь
        $key_users = AuthAssignment::getUserListByRole('elk_user_gxk');
        foreach ($key_users as $key_user)
        {
            $recipients[] = UserProfile::getProfileByUserId($key_user)->card_id;
        }
        //Работник контролируемого подразделения
        $idmanager  = DepartmentData::getCommissionListByElkIdDepartment($model->id_department_kontrolled);
        foreach ($idmanager as $manager)
        {
            $recipients[] = UserProfile::getProfileByCardId($manager)->card_id;
        }
        //Работник контролирующего подразделения
        $idmanager  = DepartmentData::getCommissionListByElkIdDepartment($model->id_department_kontrolling);
        foreach ($idmanager as $manager)
        {
            $recipients[] = UserProfile::getProfileByCardId1($manager)->card_id;
        }
        //Сопровождающий в таблице Реестр
        $recipients[] = $model->manager;
        //Работник указаный в поле Создано в Реестре
        $recipients[] =  UserProfile::getProfileByUserId($model->user_first)->card_id;

//        $recipients = array_merge($recipients1,$recipients2,$managermodel);
        return $recipients;
    }

    //****************** Ночное задание *******************************************************

    public static function getAttantionActionRecipient($model)
    {
        //Ответственный из Реестра
        $recipients[] = $model->id_otvetst;
        return $recipients;
    }

    public static function getDelayActionRecipient($model)
    {
        $recipients[] = $model->id_otvetst;
        $recipients[] = $model->id_kontrol;
        $recipients[] = $model->manager;
        //Работник контролируемого подразделения
        $idmanager  = DepartmentData::getCommissionListByElkIdDepartment($model->id_department_kontrolled);
        foreach ($idmanager as $manager)
        {
            $recipients[] = UserProfile::getProfileByCardId($manager)->card_id;
        }

        return $recipients;
    }

    public static function getAttantionActionEventsRecipient($model)
    {
        //Ответственный из Корректирующие действия
        $recipients[] = $model->id_otvetst;
        return $recipients;
    }

    public static function getDelayActionEventsRecipient($model)
    {
        $recipients[] = $model->id_otvetst;
        $recipients[] = $model->id_kontrol;
        $recipients[] = $model->manager;
        //Работник контролируемого подразделения
        $idmanager  = DepartmentData::getCommissionListByElkIdDepartment($model->id_department_kontrolled);
        foreach ($idmanager as $manager)
        {
            $recipients[] = UserProfile::getProfileByCardId($manager)->card_id;
        }

        return $recipients;
    }

    //******************************************************************************************

    public static function prepareRecipientList($recipients)
    {
        $rows = [];
        foreach ($recipients as $row)
        {
            if (!is_null($row)) {
                $rows[] = intval($row);
            }
        }
        $recipients = array_unique($rows);
        return $recipients;
    }

    public static function sendMail($model, $recipients, $typeMail, $addition_text = null)
    {
//        $recipients = [18725];
//        $recipients = [34316];
//        $recipients[] = 18725;
        $recipients = self::prepareRecipientList($recipients);

        foreach ($recipients as $recipient) {
            $profile = UserProfile::getProfileByCardId($recipient);
//            $profiles = \common\modules\userProfile\models\UserProfile::find()->where(['id' => $recipient])->one();
            if (isset($profile)){
                $user = $profile->user;
                if ($user->status == User::STATUS_ACTIVE) {
                    MailHelper::sendMail($model, $user, $typeMail['fileName'], $typeMail['subject'], $typeMail['text'] . $addition_text, 'elk');
                }
            }
        }
    }


}