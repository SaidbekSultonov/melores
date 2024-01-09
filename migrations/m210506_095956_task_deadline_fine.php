<?php

use yii\db\Migration;

/**
 * Class m210506_095956_task_deadline_fine
 */
class m210506_095956_task_deadline_fine extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = "ALTER TABLE tasks ADD COLUMN deadline_fine integer";
        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210506_095956_task_deadline_fine cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210506_095956_task_deadline_fine cannot be reverted.\n";

        return false;
    }
    */
}
