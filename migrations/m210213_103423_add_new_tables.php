<?php

use yii\db\Migration;

/**
 * Class m210213_103423_add_new_tables
 */
class m210213_103423_add_new_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = "
        CREATE TABLE IF NOT EXISTS public.step_order
        (
            id serial NOT NULL,
            chat_id INTEGER,
            step_1 INTEGER,
            step_2 INTEGER,
            PRIMARY KEY (id)
        );";

        $this->execute($sql);

        $sql = "
        CREATE TABLE IF NOT EXISTS public.last_id_order
        (
            id serial NOT NULL,
            chat_id INTEGER,
            last_id INTEGER,
            PRIMARY KEY (id)
        );";

        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210213_103423_add_new_tables cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210213_103423_add_new_tables cannot be reverted.\n";

        return false;
    }
    */
}
