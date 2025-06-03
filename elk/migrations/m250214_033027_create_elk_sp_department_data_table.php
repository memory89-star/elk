<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%elk_sp_department_data}}`.
 */
class m250214_033027_create_elk_sp_department_data_table extends Migration
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
        /* Справочник "Подразделения ЭЛК" */
        $this->createTable('{{%elk_sp_department_data}}', [
            'id' => $this->primaryKey(),
            'emp_department_id' => $this->integer()->comment('Код подразделения'),          /*Ключ общесистемной таблицы «Подразделения» emp_department*/
            'emp_department_code' => $this->string(100)->comment('Мнемокод подразделения'), /*(Для истории) Поле code из общесистемной таблицы «Подразделения» emp_department*/
            'emp_department_name' => $this->string(100)->comment('Краткое наименование подразделения'),    /*(Для истории, РЕД) Поле name из общесистемной таблицы «Подразделения» emp_department*/
            'emp_department_full_name' => $this->string(254)->comment('Полное наименование подразделения'),/*(Для истории, РЕД) Поле full_name из общесистемной таблицы «Подразделения» emp_department*/
            'emp_department_type' => $this->string(254)->comment('Тип подразделения'),/*(Для истории, РЕД) Выбор значения из выпадающего списка: «Контролируемое/Контролирующее»*/
            'doc_num_max' => $this->integer()->comment('Максимальный № документа в контролируемом подразделении'),/*(в подразделении, умолч 0) Автоматически обновляется (счётчик +1) при создании следующего документа по данному подразделению (по полю  emp_department_id)*/
            'block' => $this->string(100)->comment('Блокировка'),                   /*1 Да, 0 Нет (по умолчанию)*/
            'created_at' => 'timestamp not null',
            'updated_at' => 'timestamp not null',
            'user_last' => $this->integer()->comment('Последний пользователь'),
            'user_first' => $this->integer()->comment('Создавший пользователь'),
            'time_create' => $this->string(100)->comment('Время создания'),
            'time_update' => $this->string(100)->comment('Время редактирования'),
        ]);
        $this->createIndex('idx-elk_sp_department_data-emp_department_id', 'elk_sp_department_data', 'emp_department_id');
        $this->createIndex('idx-elk_sp_department_data-emp_department_code', 'elk_sp_department_data', 'emp_department_code');
        $this->addCommentOnTable('elk_sp_department_data','Общие данные по подразделению');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        /* Справочник "Подразделения ЭЛК" */
        $this->addCommentOnTable('elk_sp_department_data','');
        $this->dropIndex('idx-elk_sp_department_data-emp_department_code', 'elk_sp_department_data');
        $this->dropIndex('idx-elk_sp_department_data-emp_department_id', 'elk_sp_department_data');
        $this->dropTable('elk_sp_department_data');
    }
}
