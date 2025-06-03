<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%elk_sp_significance}}`.
 */
class m250214_032923_create_elk_sp_significance_table extends Migration
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
        /* Справочник "Значимость несоответствия" */
        $this->createTable('{{%elk_sp_significance}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(100)->comment('Описание'),          //Описание
            'block' => $this->string(100)->comment('Блокировка'),           /*1 Да, 0 Нет (по умолчанию)*/
            'created_at' => 'timestamp not null',
            'updated_at' => 'timestamp not null',
            'user_last' => $this->integer()->comment('Последний пользователь'),
            'user_first' => $this->integer()->comment('Создавший пользователь'),
            'time_create' => $this->string(100)->comment('Время создания'),
            'time_update' => $this->string(100)->comment('Время редактирования'),
        ]);

        $this->createIndex('idx-elk_sp_significance-name', 'elk_sp_significance', 'name');
        $this->addCommentOnTable('elk_sp_significance','Значимость несоответствия');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        /* Справочник "Значимость несоответствия" */
        $this->addCommentOnTable('elk_sp_significance','');
        $this->dropIndex('idx-elk_sp_significance-name', 'elk_sp_significance');
        $this->dropTable('elk_sp_significance');
    }
}
