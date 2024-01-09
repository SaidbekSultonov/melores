<?php

use yii\db\Migration;

/**
 * Class m210608_052104_salary_task_new_tables
 */
class m210608_052104_salary_task_new_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('salary_category', 'balance', $this->integer());

        $this->createTable('task_message_id', [
            'id' => $this->primaryKey(),
            'chat_id' => $this->integer(),
            'message_id' => $this->integer(),
        ]);

        $this->createTable('salary_user_balance', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'balance' => $this->integer(),
        ]);

        $this->createTable('salary_event_balance', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'receiver' => $this->integer(),
            'quantity' => $this->integer(),
            'category_id' => $this->integer(),
            'date' => $this->timestamp(),
            'type' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210608_052104_salary_task_new_tables cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210608_052104_salary_task_new_tables cannot be reverted.\n";

        return false;
    }
    */
}
