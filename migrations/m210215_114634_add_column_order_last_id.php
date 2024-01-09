<?php

use yii\db\Migration;

/**
 * Class m210215_114634_add_column_order_last_id
 */
class m210215_114634_add_column_order_last_id extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = "ALTER TABLE last_id_order ADD COLUMN type integer;";
        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210215_114634_add_column_order_last_id cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210215_114634_add_column_order_last_id cannot be reverted.\n";

        return false;
    }
    */
}
