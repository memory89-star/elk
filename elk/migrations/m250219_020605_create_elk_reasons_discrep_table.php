<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%elk_reasons_discrep}}`.
 */
class m250219_020605_create_elk_reasons_discrep_table extends Migration
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
        /* Таблица "Причины несоответствия" */
        $this->createTable('{{%elk_reasons_discrep}}', [
            'id' => $this->primaryKey(),
            'id_reestr' => $this->integer()->comment('Код таблицы Реестр ЛК'), //Код таблицы Реестр ЛК
            'doc_num' => $this->integer()->comment('Порядковый номер'),/*Порядковый номер записи для внешнего ключа таблицы «Реестр ЛК»*/
            'discrepancy' => $this->string(250)->comment('Причина несоответствия'),
            'created_at' => 'timestamp not null',
            'updated_at' => 'timestamp not null',
            'user_last' => $this->integer()->comment('Последний пользователь'),
            'user_first' => $this->integer()->comment('Создавший пользователь'),
            'time_create' => $this->string(100)->comment('Время создания'),
            'time_update' => $this->string(100)->comment('Время редактирования'),
        ]);
        $this->addForeignKey('fk-elk_reasons_discrep_id_reestr', 'elk_reestr', 'id_reestr', 'elk_reestr', 'id', 'RESTRICT', 'RESTRICT');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%elk_reasons_discrep}}');
    }
}
