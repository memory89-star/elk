<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%elk_sp_step}}`.
 */
class m250214_032616_create_elk_sp_step_table extends Migration
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
        $this->createTable('{{%elk_sp_step}}', [
            'id' => $this->primaryKey(),
            'index_namber' => $this->integer()->comment('№ п/п'), //Порядковый номер записи, вводится вручную пользователем
            'name' => $this->string(100)->comment('Наименование'),          //Описание
            'v_for_creating_doc' => $this->boolean()->defaultValue(false)->comment('Значение при создании документа'),
            'v_after_accept_event' => $this->boolean()->defaultValue(false)->comment('Установить после заполнения Мероприятия (блок "Коррекции")'), //доп. галочка
            'block' => $this->string(100)->comment('Блокировка'),           /*1 Да, 0 Нет (по умолчанию)*/
            'created_at' => 'timestamp not null',
            'updated_at' => 'timestamp not null',
            'user_last' => $this->integer()->comment('Последний пользователь'),
            'user_first' => $this->integer()->comment('Создавший пользователь'),
            'time_create' => $this->string(100)->comment('Время создания'),
            'time_update' => $this->string(100)->comment('Время редактирования'),
        ]);
        $this->createIndex('idx-elk_sp_step-name', 'elk_sp_step', 'name');
        $this->addCommentOnTable('elk_sp_step','Этапы реализации');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        /* Справочник "Этапы реализации" */
        $this->addCommentOnTable('elk_sp_step','');
        $this->dropIndex('idx-elk_sp_step-name', 'elk_sp_step');
        $this->dropTable('elk_sp_step');
    }
}
