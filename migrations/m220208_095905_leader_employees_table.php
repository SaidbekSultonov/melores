<?php

use yii\db\Migration;

/**
 * Class m220208_095905_leader_employees_table
 */
class m220208_095905_leader_employees_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('leader_employees', [
            'id' => $this->primaryKey(),
            'leader_id' => $this->integer(),
            'employee_id' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('leader_employees');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220208_095905_leader_employees_table cannot be reverted.\n";

        return false;
    }
    */
}
