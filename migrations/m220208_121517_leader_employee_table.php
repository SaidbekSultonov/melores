<?php

use yii\db\Migration;

/**
 * Class m220208_121517_leader_employee_table
 */
class m220208_121517_leader_employee_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // $this->dropColumn('leader_employees');
        $tableName = $this->db->tablePrefix . 'leader_employees';
        if ($this->db->getTableSchema($tableName, true) === null) {
            $this->createTable('leader_employees', [
                'id' => $this->primaryKey(),
                'leader_id' => $this->integer(),
                'employee_id' => $this->integer(),
            ]);
        }
        
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m220208_121517_leader_employee_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220208_121517_leader_employee_table cannot be reverted.\n";

        return false;
    }
    */
}
