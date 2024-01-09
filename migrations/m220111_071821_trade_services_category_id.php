<?php

use yii\db\Migration;

/**
 * Class m220111_071821_trade_services_category_id
 */
class m220111_071821_trade_services_category_id extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('trade_services', 'category_id', $this->integer(11));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('trade_services', 'category_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220111_071821_trade_services_category_id cannot be reverted.\n";

        return false;
    }
    */
}
