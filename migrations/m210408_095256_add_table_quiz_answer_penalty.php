<?php

use yii\db\Migration;

/**
 * Class m210408_095256_add_table_quiz_answer_penalty
 */
class m210408_095256_add_table_quiz_answer_penalty extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('quiz', [
            'id' => $this->primaryKey(),
            'question' => $this->text(),
            'status' => $this->smallInteger()
        ]);

        $this->createTable('answer', [
            'id' => $this->primaryKey(),
            'answer' => $this->text(),
            'user_id' => $this->integer(),
            'quiz_id' => $this->integer(),
            'date' => $this->timestamp(),
            'status' => $this->smallInteger()
        ]);
        
        $this->createTable('quiz_step', [
            'id' => $this->primaryKey(),
            'chat_id' => $this->integer(),
            'step_1' => $this->integer(),
            'step_2' => $this->integer()
        ]);
            
        $this->createTable('penalties', [
            'id' => $this->primaryKey(),
            'sum' => $this->double(),
            'start_date' => $this->timestamp(),
            'end_date' => $this->timestamp()
        ]);
        
        $this->createTable('penalty_users', [
            'id' => $this->primaryKey(),
            'penalty_sum' => $this->double(),
            'user_id' => $this->integer(),
            'penalty_id' => $this->integer(),
            'date' => $this->timestamp()
        ]);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210408_095256_add_table_quiz_answer_penalty cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210408_095256_add_table_quiz_answer_penalty cannot be reverted.\n";

        return false;
    }
    */
}
