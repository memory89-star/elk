<?php

use yii\db\Migration;

/**
 * Class m250306_034305_add_elk_department_restr_table
 */
class m250306_034305_add_elk_department_restr_table extends Migration
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
        $this->addColumn('elk_reestr','manager', $this->string(150));
        $this->addColumn('elk_sp_department_data','manager', $this->string(150));

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m250306_034305_add_elk_department_restr_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250306_034305_add_elk_department_restr_table cannot be reverted.\n";

        return false;
    }
    */
}
