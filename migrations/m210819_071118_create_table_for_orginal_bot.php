<?php

use yii\db\Migration;

/**
 * Class m210819_071118_create_table_for_orginal_bot
 */
class m210819_071118_create_table_for_orginal_bot extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('orginal_message_id', [
            'id' => $this->primaryKey(),
            'chat_id' => $this->integer(),
            'message_id' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210819_071118_create_table_for_orginal_bot cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210819_071118_create_table_for_orginal_bot cannot be reverted.\n";

        return false;
    }
    */
}
