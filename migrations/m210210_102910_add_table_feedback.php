<?php

use yii\db\Migration;

/**
 * Class m210210_102910_add_table_feedback
 */
class m210210_102910_add_table_feedback extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = "
        CREATE TABLE IF NOT EXISTS public.feedback_user
        (
            id serial NOT NULL,
            title CHARACTER VARYING(50),
            status SMALLINT,
            type SMALLINT,
            PRIMARY KEY (id)
        );";

        $this->execute($sql);


        $sql = "
        CREATE TABLE IF NOT EXISTS public.user_balls
        (
            id serial NOT NULL,
            feedback_user_id INTEGER,
            ball DOUBLE PRECISION,
            created_date TIMESTAMP,
            chat_id INTEGER,
            order_id INTEGER,
            PRIMARY KEY (id)
        );";

        $this->execute($sql);

        $sql = "ALTER TABLE orders ADD COLUMN chat_id integer;";
        $this->execute($sql);

        $sql = "ALTER TABLE orders ADD COLUMN category_id integer;";
        $this->execute($sql);

        $sql = "ALTER TABLE order_materials ADD COLUMN chat_id integer;";
        $this->execute($sql);

        $sql = "ALTER TABLE clients ADD COLUMN branch_id integer;";
        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210210_102910_add_table_feedback cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210210_102910_add_table_feedback cannot be reverted.\n";

        return false;
    }
    */
}
