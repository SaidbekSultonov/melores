<?php

use yii\db\Migration;

/**
 * Class m210408_130802_add_table_send_quiz
 */
class m210408_130802_add_table_send_quiz extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('send_quiz', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'quiz_id' => $this->integer(),
            'status' => $this->smallInteger()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210408_130802_add_table_send_quiz cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210408_130802_add_table_send_quiz cannot be reverted.\n";

        return false;
    }
    */
}
