<?php

use yii\db\Migration;

/**
 * Class m230606_022219_change_orders_id_increment
 */
class m230606_022219_change_orders_id_increment extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        
        
        $sql = `SELECT pg_catalog.setval(pg_get_serial_sequence('branch', 'id'), MAX(id)) FROM branch;`;
        $this->execute($sql);
        
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230606_022219_change_orders_id_increment cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230606_022219_change_orders_id_increment cannot be reverted.\n";

        return false;
    }
    */
}
