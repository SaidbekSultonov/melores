<?php

use yii\db\Migration;

/**
 * Class m210227_093202_add_table_order_categories
 */
class m210227_093202_add_table_order_categories extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = "
        CREATE TABLE IF NOT EXISTS public.order_categories
        (
            id serial NOT NULL,
            category_id INTEGER,
            order_id INTEGER,
            status SMALLINT,
            PRIMARY KEY (id)
        );";

        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210227_093202_add_table_order_categories cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210227_093202_add_table_order_categories cannot be reverted.\n";

        return false;
    }
    */
}
