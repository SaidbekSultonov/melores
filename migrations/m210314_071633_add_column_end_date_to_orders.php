<?php

use yii\db\Migration;

/**
 * Class m210314_071633_add_column_end_date_to_orders
 */
class m210314_071633_add_column_end_date_to_orders extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = "ALTER TABLE orders ADD COLUMN end_date integer;";
        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210314_071633_add_column_end_date_to_orders cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210314_071633_add_column_end_date_to_orders cannot be reverted.\n";

        return false;
    }
    */
}
