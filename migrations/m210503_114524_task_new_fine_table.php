<?php

use yii\db\Migration;

/**
 * Class m210503_114524_task_new_fine_table
 */
class m210503_114524_task_new_fine_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('task_fine_deadline', [
            'id' => $this->primaryKey(),
            'price' => $this->integer(),
            'task_id' => $this->integer(),
            'user_id' => $this->integer()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210503_114524_task_new_fine_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210503_114524_task_new_fine_table cannot be reverted.\n";

        return false;
    }
    */
}
