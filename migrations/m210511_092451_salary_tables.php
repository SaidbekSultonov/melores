<?php

use yii\db\Migration;

/**
 * Class m210511_092451_salary_tables
 */
class m210511_092451_salary_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('salary_category', [
            'id' => $this->primaryKey(),
            'title' => $this->string(),
        ]);

        $this->createTable('salary_answer', [
            'id' => $this->primaryKey(),
            'title' => $this->string(),
            'status' => $this->integer(),
        ]);

        $this->createTable('salary_step', [
            'id' => $this->primaryKey(),
            'chat_id' => $this->integer(),
            'step_1' => $this->smallInteger(),
            'step_2' => $this->smallInteger()
        ]);

        $this->createTable('salary_amount', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'chat_id' => $this->integer(),
            'category_id' => $this->integer(),
            'price' => $this->integer(),
            'comment' => $this->string(),
            'date' => $this->timestamp(),
            'type' => $this->integer(),
            'status' => $this->integer()
        ]);

        $this->createTable('salary_last_id', [
            'id' => $this->primaryKey(),
            'chat_id' => $this->integer(),
            'last_id' => $this->integer()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210511_092451_salary_tables cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210511_092451_salary_tables cannot be reverted.\n";

        return false;
    }
    */
}
