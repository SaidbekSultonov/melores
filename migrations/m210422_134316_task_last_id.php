<?php

use yii\db\Migration;

/**
 * Class m210422_134316_task_last_id
 */
class m210422_134316_task_last_id extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('task_last_id', [
            'id' => $this->primaryKey(),
            'chat_id' => $this->integer(),
            'last_id' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210422_134316_task_last_id cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210422_134316_task_last_id cannot be reverted.\n";

        return false;
    }
    */
}
