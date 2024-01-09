<?php

use yii\db\Migration;

/**
 * Class m210310_122003_add_column_to_section_orders_step
 */
class m210310_122003_add_column_to_section_orders_step extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = "ALTER TABLE section_orders ADD COLUMN step SMALLINT;";
        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210310_122003_add_column_to_section_orders_step cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210310_122003_add_column_to_section_orders_step cannot be reverted.\n";

        return false;
    }
    */
}
