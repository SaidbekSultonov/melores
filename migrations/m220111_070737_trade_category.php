<?php

use yii\db\Migration;

/**
 * Class m220111_070737_trade_category
 */
class m220111_070737_trade_category extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('trade_category', [
            'id' => $this->primaryKey(),
            'title_uz' => $this->string(150),
            'title_ru' => $this->string(150),
            'status' => $this->integer()->defaultValue(1),
        ]);

        
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('trade_category');

        
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220111_070737_trade_category cannot be reverted.\n";

        return false;
    }
    */
}
