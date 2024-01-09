<?php

use yii\db\Migration;

/**
 * Class m210425_200611_add_column_order_column_2_to_order_step
 */
class m210425_200611_add_column_order_column_2_to_order_step extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = "ALTER TABLE order_step ADD COLUMN order_column_2 smallint";
        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210425_200611_add_column_order_column_2_to_order_step cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210425_200611_add_column_order_column_2_to_order_step cannot be reverted.\n";

        return false;
    }
    */
}
