<?php

use yii\db\Migration;

/**
 * Class m210321_125512_add_column_to_orders_parralel
 */
class m210321_125512_add_column_to_orders_parralel extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = "ALTER TABLE orders ADD COLUMN parralel SMALLINT";
        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210321_125512_add_column_to_orders_parralel cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210321_125512_add_column_to_orders_parralel cannot be reverted.\n";

        return false;
    }
    */
}
