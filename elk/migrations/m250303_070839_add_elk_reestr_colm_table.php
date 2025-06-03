<?php

use yii\db\Migration;

/**
 * Class m250303_070839_add_elk_reestr_colm_table
 */
class m250303_070839_add_elk_reestr_colm_table extends Migration
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
        $this->addColumn('elk_reestr','year',$this->string(32));
        $this->addColumn('elk_reestr','month',$this->string(32));
        $this->addColumn('elk_reestr','status',$this->string(100));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m250303_070839_add_elk_reestr_colm_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250303_070839_add_elk_reestr_colm_table cannot be reverted.\n";

        return false;
    }
    */
}
