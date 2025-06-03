<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%elk_reestr}}`.
 */
class m250214_033637_create_elk_reestr_table extends Migration
{
    public function init() {
        $this->db = 'elk';

        parent::init();
    }
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        /* Таблица "Реестр летучего контроля" */
        $this->createTable('{{%elk_reestr}}', [
            'id' => $this->primaryKey(),
            'id_department_kontrolling' => $this->integer()->comment('Контролирующее подразделение'), //контролирующее подразделение
            'id_department_kontrolled' => $this->integer()->comment('Контролируемое подразделение'), //контролируемое подразделение
            'id_objects' => $this->integer()->comment('Код объекта ЛК'), //справочник Коды объекта ЛК elk_sp_kod_lk
            'opisan' => $this->string(250)->comment('Описание объекта ЛК'), //справочник Коды объекта ЛК elk_sp_kod_lk
            'id_significance' => $this->integer()->comment('Код значимости'), //справочник Значимость несоответствия elk_sp_significance
            'id_step' => $this->integer()->comment('Код Этапа реализации'), //справочник Этапы реализации elk_sp_step
            'id_otvetst' => $this->integer()->comment('Код Ответственный'), //справочник Работники  emp_card
            'id_kontrol' => $this->integer()->comment('Код контролирующий'), //справочник Работники  emp_card
            'date_detection' => $this->date()->comment('Дата выявления'), //дата выявления несоответствия
            'date_registr' => $this->date()->comment('Дата регистрации'), //дата регистрации несоответствия
            'identification_document_number' => $this->string(25)->comment('Регистрационный номер документа ЭЛК'),
            'incongruity' => $this->string(250)->comment('Несоответствие'), //описание несоответствия
            'requirements_not_met' => $this->string(250)->comment('не выполнены требования'), //описание невыполненных требований
            'reason_modification' => $this->string(250)->comment('причина доработки'), //описание причины доработки
            'events_elimination' => $this->string(250)->comment('мероприятия по устранению несоответствий'), //описание мероприятий по устранению несоответствий
            'date_plan' => $this->date()->comment('плановая дата'), //плановая дата устранения несоответствия
            'date_fact' => $this->date()->comment('фактическая дата'), //фактическая дата устранения несоответствия
            'created_at' => 'timestamp not null',
            'updated_at' => 'timestamp not null',
            'user_last' => $this->integer()->comment('Последний пользователь'),
            'user_first' => $this->integer()->comment('Создавший пользователь'),
            'time_create' => $this->string(100)->comment('Время создания'),
            'time_update' => $this->string(100)->comment('Время редактирования'),
        ]);
        $this->addForeignKey('fk-elk_reestr_id_department_kontrolling', 'elk_reestr', 'id_department_kontrolling', 'elk_sp_department_data', 'id', 'RESTRICT', 'RESTRICT');
        $this->addForeignKey('fk-elk_reestr_id_department_kontrolled', 'elk_reestr', 'id_department_kontrolled', 'elk_sp_department_data', 'id', 'RESTRICT', 'RESTRICT');
        $this->addForeignKey('fk-elk_reestr_id_objects', 'elk_reestr', 'id_objects', 'elk_sp_kod', 'id', 'RESTRICT', 'RESTRICT');
        $this->addForeignKey('fk-elk_reestr_id_significance', 'elk_reestr', 'id_significance', 'elk_sp_significance', 'id', 'RESTRICT', 'RESTRICT');
        $this->addForeignKey('fk-elk_reestr_id_step', 'elk_reestr', 'id_step', 'elk_sp_step', 'id', 'RESTRICT', 'RESTRICT');
        $this->addCommentOnTable('elk_reestr', 'Реестр летучего контроля');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%elk_reestr}}');
    }
}
