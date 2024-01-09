<?php

use yii\db\Migration;

/**
 * Class m210219_083953_create_table_bot_users
 */
class m210219_083953_create_table_bot_users extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = "
        CREATE TABLE IF NOT EXISTS public.bot
        (
            id serial NOT NULL,
            username INTEGER,
            token CHARACTER VARYING,
            link CHARACTER VARYING,
            PRIMARY KEY (id)
        );";
        $this->execute($sql);


        $sql = "
        CREATE TABLE IF NOT EXISTS public.bot_users
        (
            id serial NOT NULL,
            user_id INTEGER,
            bot_id INTEGER,
            PRIMARY KEY (id)
        );";
        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210219_083953_create_table_bot_users cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210219_083953_create_table_bot_users cannot be reverted.\n";

        return false;
    }
    */
}
