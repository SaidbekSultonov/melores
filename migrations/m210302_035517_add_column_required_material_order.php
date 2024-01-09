<?php

use yii\db\Migration;

/**
 * Class m210302_035517_add_column_required_material_order
 */
class m210302_035517_add_column_required_material_order extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = "ALTER TABLE required_material_order ADD COLUMN message_id integer;";
        $this->execute($sql);

        $sql = "ALTER TABLE required_material_order ADD COLUMN text TEXT;";
        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210302_035517_add_column_required_material_order cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210302_035517_add_column_required_material_order cannot be reverted.\n";

        return false;
    }
    */
}
