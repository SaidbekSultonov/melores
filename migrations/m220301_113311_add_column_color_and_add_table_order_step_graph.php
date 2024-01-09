<?php

use yii\db\Migration;

/**
 * Class m220301_113311_add_column_color_and_add_table_order_step_graph
 */
class m220301_113311_add_column_color_and_add_table_order_step_graph extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('order_step_graph', [
            'id' => $this->primaryKey(),
            'order_id' => $this->integer(11),
            'section_id' => $this->integer(11),
            'start_date' => $this->timestamp(),
            'end_date' => $this->timestamp(),
            'order_column' => $this->integer(2),
            'status' => $this->smallInteger(1),
            'work_hour' => $this->integer(3)
        ]);

         $this->addColumn('sections', 'color', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m220301_113311_add_column_color_and_add_table_order_step_graph cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220301_113311_add_column_color_and_add_table_order_step_graph cannot be reverted.\n";

        return false;
    }
    */
}
