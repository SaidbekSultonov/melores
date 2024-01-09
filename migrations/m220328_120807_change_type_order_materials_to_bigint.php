<?php

use yii\db\Migration;

/**
 * Class m220328_120807_change_type_order_materials_to_bigint
 */
class m220328_120807_change_type_order_materials_to_bigint extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = "ALTER TABLE order_materials ALTER COLUMN chat_id TYPE BIGINT;";
        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m220328_120807_change_type_order_materials_to_bigint cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220328_120807_change_type_order_materials_to_bigint cannot be reverted.\n";

        return false;
    }
    */
}
