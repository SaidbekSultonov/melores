<?php

use yii\db\Migration;

/**
 * Class m210305_093356_create_table_paused_orders
 */
class m210305_093356_create_table_paused_orders extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = "
        CREATE TABLE IF NOT EXISTS public.paused_orders
        (
            id serial NOT NULL,
            order_id INTEGER,
            start_date DATE,
            end_date DATE,
            PRIMARY KEY (id)
        );";
        
        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210305_093356_create_table_paused_orders cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210305_093356_create_table_paused_orders cannot be reverted.\n";

        return false;
    }
    */
}
