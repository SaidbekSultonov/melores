<?php

use yii\db\Migration;

/**
 * Class m210423_233535_task_change_deadline_type
 */
class m210423_233535_task_change_deadline_type extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = "ALTER TABLE tasks 
    ALTER COLUMN dead_line TYPE VARCHAR;";
        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210423_233535_task_change_deadline_type cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210423_233535_task_change_deadline_type cannot be reverted.\n";

        return false;
    }
    */
}
