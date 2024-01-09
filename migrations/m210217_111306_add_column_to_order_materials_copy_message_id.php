<?php

use yii\db\Migration;

/**
 * Class m210217_111306_add_column_to_order_materials_copy_message_id
 */
class m210217_111306_add_column_to_order_materials_copy_message_id extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = "ALTER TABLE order_materials ADD COLUMN copy_message_id integer;";
        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210217_111306_add_column_to_order_materials_copy_message_id cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210217_111306_add_column_to_order_materials_copy_message_id cannot be reverted.\n";

        return false;
    }
    */
}
