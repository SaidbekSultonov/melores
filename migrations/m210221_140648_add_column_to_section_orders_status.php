<?php

use yii\db\Migration;

/**
 * Class m210221_140648_add_column_to_section_orders_status
 */
class m210221_140648_add_column_to_section_orders_status extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = "ALTER TABLE section_orders ADD COLUMN status integer DEFAULT 1;";
        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210221_140648_add_column_to_section_orders_status cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210221_140648_add_column_to_section_orders_status cannot be reverted.\n";

        return false;
    }
    */
}
