<?php

use yii\db\Migration;

/**
 * Class m210507_112825_task_new_column_for_task_status
 */
class m210507_112825_task_new_column_for_task_status extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = "ALTER TABLE task_status ADD COLUMN task_deadline_date timestamp";
        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210507_112825_task_new_column_for_task_status cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210507_112825_task_new_column_for_task_status cannot be reverted.\n";

        return false;
    }
    */
}
