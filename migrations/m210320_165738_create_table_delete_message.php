<?php

use yii\db\Migration;

/**
 * Class m210320_165738_create_table_delete_message
 */
class m210320_165738_create_table_delete_message extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = "
        CREATE TABLE IF NOT EXISTS public.delete_messages
        (
            id serial NOT NULL,
            chat_id INTEGER,
            message_id INTEGER,
            PRIMARY KEY (id)
        );";

        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210320_165738_create_table_delete_message cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210320_165738_create_table_delete_message cannot be reverted.\n";

        return false;
    }
    */
}
