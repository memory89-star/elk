<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%elk_events}}`.
 */
class m250219_043339_create_elk_events_table extends Migration
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
        $this->createTable('{{%elk_events}}', [
            'id' => $this->primaryKey(),
            'id_reestr' => $this->integer()->comment('Код таблицы Реестр ЛК'), //Код таблицы Реестр ЛК
            'id_discrepancy' => $this->integer()->comment('Код таблицы Причины несоответствия'), //Код таблицы Причины несоответствия
            'doc_num' => $this->integer()->comment('Порядковый номер'),
            'events' => $this->string(250)->comment('Мероприятие по устранению причин'),
            'date_plan' => $this->date()->comment('Плановый срок'), //плановая дата устранения несоответствия
            'date_fact' => $this->date()->comment('Фактическая дата'), //фактическая дата устранения несоответствия
            'id_otvetst' => $this->integer()->comment('Код Ответственный'), //справочник Работники  emp_card
            'id_kontrol' => $this->integer()->comment('Код Контролирующий'), //справочник Работники  emp_card
            'created_at' => 'timestamp not null',
            'updated_at' => 'timestamp not null',
            'user_last' => $this->integer()->comment('Последний пользователь'),
            'user_first' => $this->integer()->comment('Создавший пользователь'),
            'time_create' => $this->string(100)->comment('Время создания'),
            'time_update' => $this->string(100)->comment('Время редактирования'),
        ]);
        $this->addForeignKey('fk-elk_events_id_reestr', 'elk_events', 'id_reestr', 'elk_reestr', 'id', 'RESTRICT', 'RESTRICT');
        $this->addForeignKey('fk-elk_events_id_discrepancy', 'elk_events', 'id_discrepancy', 'elk_reasons_discrep', 'id', 'RESTRICT', 'RESTRICT');
        $this->addCommentOnTable('elk_events', 'Причина несоответствия');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%elk_events}}');
    }
}
