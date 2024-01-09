<?php

use yii\db\Migration;

/**
 * Class m210308_072952_add_column_description_to_orders
 */
class m210308_072952_add_column_description_to_orders extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = "ALTER TABLE orders ADD COLUMN description TEXT;";
        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210308_072952_add_column_description_to_orders cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210308_072952_add_column_description_to_orders cannot be reverted.\n";

        return false;
    }
    */
}
