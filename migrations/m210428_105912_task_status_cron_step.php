<?php

use yii\db\Migration;

/**
 * Class m210428_105912_task_status_cron_step
 */
class m210428_105912_task_status_cron_step extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = "ALTER TABLE task_status ADD COLUMN step_cron integer";
        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210428_105912_task_status_cron_step cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210428_105912_task_status_cron_step cannot be reverted.\n";

        return false;
    }
    */
}
