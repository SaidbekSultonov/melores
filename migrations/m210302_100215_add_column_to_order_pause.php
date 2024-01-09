<?php

use yii\db\Migration;

/**
 * Class m210302_100215_add_column_to_order_pause
 */
class m210302_100215_add_column_to_order_pause extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = "ALTER TABLE orders ADD COLUMN pause smallint;";
        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210302_100215_add_column_to_order_pause cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210302_100215_add_column_to_order_pause cannot be reverted.\n";

        return false;
    }
    */
}
