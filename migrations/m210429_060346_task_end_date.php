<?php

use yii\db\Migration;

/**
 * Class m210429_060346_task_end_date
 */
class m210429_060346_task_end_date extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = "ALTER TABLE task_status ADD COLUMN task_end_date timestamp";
        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210429_060346_task_end_date cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210429_060346_task_end_date cannot be reverted.\n";

        return false;
    }
    */
}
