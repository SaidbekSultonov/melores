<?php

use yii\db\Migration;

/**
 * Class m210308_070304_create_table_section_orders_controll
 */
class m210308_070304_create_table_section_orders_controll extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = "
        CREATE TABLE IF NOT EXISTS public.section_orders_control
        (
            id serial NOT NULL,
            order_id INTEGER,
            section_id INTEGER,
            enter_date TIMESTAMP,
            exit_date TIMESTAMP,
            PRIMARY KEY (id)
        );";

        $this->execute($sql);

        $sql = "
        CREATE TABLE IF NOT EXISTS public.required_materials
        (
            id serial NOT NULL,
            title CHARACTER VARYING,
            PRIMARY KEY (id)
        );";

        $this->execute($sql);

        $sql = "ALTER TABLE required_material_order ADD COLUMN required_material_id integer;";
        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210308_070304_create_table_section_orders_controll cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210308_070304_create_table_section_orders_controll cannot be reverted.\n";

        return false;
    }
    */
}
