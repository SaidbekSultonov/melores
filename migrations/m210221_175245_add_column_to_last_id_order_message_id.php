<?php

use yii\db\Migration;

/**
 * Class m210221_175245_add_column_to_last_id_order_message_id
 */
class m210221_175245_add_column_to_last_id_order_message_id extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = "ALTER TABLE last_id_order ADD COLUMN message_id integer;";
        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210221_175245_add_column_to_last_id_order_message_id cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210221_175245_add_column_to_last_id_order_message_id cannot be reverted.\n";

        return false;
    }
    */
}
