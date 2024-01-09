<?php

use yii\db\Migration;

/**
 * Class m210623_065900_alter_column_dates_to_paused_orders
 */
class m210623_065900_alter_column_dates_to_paused_orders extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = "ALTER TABLE paused_orders
        ALTER COLUMN start_date TYPE TIMESTAMP";
        $this->execute($sql);

        $sql2 = "ALTER TABLE paused_orders
        ALTER COLUMN end_date TYPE TIMESTAMP";
        $this->execute($sql2);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210623_065900_alter_column_dates_to_paused_orders cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210623_065900_alter_column_dates_to_paused_orders cannot be reverted.\n";

        return false;
    }
    */
}
