<?php

use yii\db\Migration;

/**
 * Class m220203_100344_trade_log
 */
class m220203_100344_trade_log extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('trade_log', [
            'id' => $this->primaryKey(),
            'chat_id' => $this->integer(),
            'category_id' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('trade_log');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220203_100344_trade_log cannot be reverted.\n";

        return false;
    }
    */
}
