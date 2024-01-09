<?php

use yii\db\Migration;

/**
 * Class m210314_071912_add_column_end_date_to_orders_2
 */
class m210314_071912_add_column_end_date_to_orders_2 extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $sql = "ALTER TABLE orders DROP COLUMN end_date;";
        $this->execute($sql);

        $sql = "ALTER TABLE orders ADD COLUMN end_date DATE;";
        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210314_071912_add_column_end_date_to_orders_2 cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210314_071912_add_column_end_date_to_orders_2 cannot be reverted.\n";

        return false;
    }
    */
}
