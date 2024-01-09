<?php

use yii\db\Migration;

/**
 * Class m210517_095246_salary_message_id
 */
class m210517_095246_salary_message_id extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('salary_message_id', [
            'id' => $this->primaryKey(),
            'chat_id' => $this->integer(),
            'message_id' => $this->integer()
        ]);

        $sql = "ALTER TABLE salary_amount DROP COLUMN user_id;";
        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210517_095246_salary_message_id cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210517_095246_salary_message_id cannot be reverted.\n";

        return false;
    }
    */
}
