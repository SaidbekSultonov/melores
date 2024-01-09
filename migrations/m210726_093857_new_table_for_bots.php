<?php

use yii\db\Migration;

/**
 * Class m210726_093857_new_table_for_bots
 */
class m210726_093857_new_table_for_bots extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('service_statistics', [
            'id' => $this->primaryKey(),
            'chat_id' => $this->integer(),
            'type' => $this->string(),
            'button_id' => $this->integer(),
            'click_count' => $this->integer(),
        ]);

        $this->createTable('trade_service_statistics', [
            'id' => $this->primaryKey(),
            'chat_id' => $this->integer(),
            'type' => $this->string(),
            'button_id' => $this->integer(),
            'click_count' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210726_093857_new_table_for_bots cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210726_093857_new_table_for_bots cannot be reverted.\n";

        return false;
    }
    */
}
