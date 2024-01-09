<?php

use yii\db\Migration;

/**
 * Class m210224_084419_addStatusForOrder_step
 */
class m210224_084419_addStatusForOrder_step extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('order_step', 'status', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210224_084419_addStatusForOrder_step cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210224_084419_addStatusForOrder_step cannot be reverted.\n";

        return false;
    }
    */
}
