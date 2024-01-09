<?php

use yii\db\Migration;

/**
 * Class m210219_114357_add_tables_for_client_bot
 */
class m210219_114357_add_tables_for_client_bot extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = "
        CREATE TABLE IF NOT EXISTS public.feedback_client
        (
            id serial NOT NULL,
            title CHARACTER VARYING(50),
            status SMALLINT,
            type SMALLINT,
            PRIMARY KEY (id)
        );";

        $this->execute($sql);


        $sql = "
        CREATE TABLE IF NOT EXISTS public.client_balls
        (
            id serial NOT NULL,
            feedback_client_id INTEGER,
            ball DOUBLE PRECISION,
            created_date TIMESTAMP,
            chat_id INTEGER,
            order_id INTEGER,
            PRIMARY KEY (id)
        );";

        $this->execute($sql);

        $sql = "
        CREATE TABLE IF NOT EXISTS public.client_last_id
        (
            id serial NOT NULL,
            chat_id INTEGER,
            last_id INTEGER,
            PRIMARY KEY (id)
        );";

        $this->execute($sql);

        $sql = "
        CREATE TABLE IF NOT EXISTS public.client_recommendation
        (
            id serial NOT NULL,
            client_id INTEGER,
            full_name INTEGER,
            phone_number CHARACTER VARYING(13),
            PRIMARY KEY (id)
        );";

        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210219_114357_add_tables_for_client_bot cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210219_114357_add_tables_for_client_bot cannot be reverted.\n";

        return false;
    }
    */
}
