<?php

use yii\db\Migration;

/**
 * Class m210307_124241_add_column_to_orders_start_time
 */
class m210307_124241_add_column_to_orders_start_time extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = "ALTER TABLE orders ADD COLUMN start_time TIMESTAMP;";
        $this->execute($sql);

        $sql = "ALTER TABLE order_step ADD COLUMN work_hour INTEGER;";
        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210307_124241_add_column_to_orders_start_time cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210307_124241_add_column_to_orders_start_time cannot be reverted.\n";

        return false;
    }
    */
}
