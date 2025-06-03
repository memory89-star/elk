<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%elk_sp_kod}}`.
 */
class m250214_032745_create_elk_sp_kod_table extends Migration
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
        /* Справочник "Коды объектов ЛК" */
        $this->createTable('{{%elk_sp_kod}}', [
            'id' => $this->primaryKey(),
            'kod_objects' => $this->integer()->comment('Код объекта'),/*(умолч 0) поле обязательно*/
            'name' => $this->string(100)->comment('Описание наименования'),          //Описание
            'block' => $this->string(100)->comment('Блокировка'),           /*1 Да, 0 Нет (по умолчанию)*/
            'created_at' => 'timestamp not null',
            'updated_at' => 'timestamp not null',
            'user_last' => $this->integer()->comment('Последний пользователь'),
            'user_first' => $this->integer()->comment('Создавший пользователь'),
            'time_create' => $this->string(100)->comment('Время создания'),
            'time_update' => $this->string(100)->comment('Время редактирования'),
        ]);
        $this->createIndex('idx-elk_sp_kod_lk-name', 'elk_sp_kod', 'name');
        $this->addCommentOnTable('elk_sp_kod','Коды объектов ЛК');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        /* Справочник "Коды объектов ЛК" */
        $this->addCommentOnTable('elk_sp_kod_lk','');
        $this->dropIndex('idx-elk_sp_kod_lk-name', 'elk_sp_kod');
        $this->dropTable('elk_sp_kod');
    }
}
