<?php

use yii\db\Migration;

/**
 * Class m210219_102151_add_column_order_column
 */
class m210219_102151_add_column_order_column extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = "ALTER TABLE order_step ADD COLUMN order_column integer;";
        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210219_102151_add_column_order_column cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210219_102151_add_column_order_column cannot be reverted.\n";

        return false;
    }
    */
}
