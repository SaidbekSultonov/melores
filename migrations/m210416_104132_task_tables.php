<?php

use yii\db\Migration;

/**
 * Class m210416_104132_task_tables
 */
class m210416_104132_task_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('tasks', [
            'id' => $this->primaryKey(),
            'admin_id' => $this->integer(),
            'task_fine' => $this->integer(),
            'dead_line' => $this->integer(),
            'status' => $this->smallInteger(),
            'created_date' => $this->timestamp()
        ]);

        $this->createTable('task_user', [
            'id' => $this->primaryKey(),
            'task_id' => $this->integer(),
            'user_id' => $this->integer(),
        ]);

        $this->createTable('task_fine', [
            'id' => $this->primaryKey(),
            'price' => $this->integer(),
            'task_id' => $this->integer(),
            'user_id' => $this->integer(),
            'created_date' => $this->timestamp(),
        ]);

        $this->createTable('task_step', [
            'id' => $this->primaryKey(),
            'chat_id' => $this->integer(),
            'step_1' => $this->smallInteger(),
            'step_2' => $this->smallInteger()
        ]);

        $this->createTable('task_materials', [
            'id' => $this->primaryKey(),
            'task_id' => $this->integer(),
            'file_id' => $this->string(),
            'caption' => $this->string(),
            'type' => $this->string()
        ]);

        $this->createTable('task_status', [
            'id' => $this->primaryKey(),
            'task_id' => $this->integer(),
            'user_id' => $this->integer(),
            'status' => $this->smallInteger(),
            'enter_date' => $this->timestamp(),
            'end_date' => $this->timestamp()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210416_104132_task_tables cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210416_104132_task_tables cannot be reverted.\n";

        return false;
    }
    */
}
