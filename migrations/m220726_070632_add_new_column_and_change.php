<?php

use yii\db\Migration;

/**
 * Class m220726_070632_add_new_column_and_change
 */
class m220726_070632_add_new_column_and_change extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('user_penalties', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'order_id' => $this->integer(),
            'delay_day_count' => $this->integer(),
            'sum' => $this->integer()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m220726_070632_add_new_column_and_change cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220726_070632_add_new_column_and_change cannot be reverted.\n";

        return false;
    }
    */
}
