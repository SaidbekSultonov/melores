<?php

use yii\db\Migration;

/**
 * Class m210219_123558_add_table_client_step
 */
class m210219_123558_add_table_client_step extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = "
        CREATE TABLE IF NOT EXISTS public.client_step
        (
            id serial NOT NULL,
            chat_id INTEGER,
            step_1 INTEGER,
            step_2 INTEGER,
            PRIMARY KEY (id)
        );";

        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210219_123558_add_table_client_step cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210219_123558_add_table_client_step cannot be reverted.\n";

        return false;
    }
    */
}
