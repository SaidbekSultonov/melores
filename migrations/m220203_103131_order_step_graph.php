<?php

use yii\db\Migration;

/**
 * Class m220203_103131_order_step_graph
 */
class m220203_103131_order_step_graph extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = "
        CREATE TABLE IF NOT EXISTS public.order_graph
        (
            id serial NOT NULL,
            order_id INTEGER,
            section_id INTEGER,
            deadline TIMESTAMP,
            PRIMARY KEY (id)
        );";

        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('order_graph');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220203_103131_order_step_graph cannot be reverted.\n";

        return false;
    }
    */
}
