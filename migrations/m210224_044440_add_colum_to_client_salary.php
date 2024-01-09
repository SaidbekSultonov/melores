<?php

use yii\db\Migration;

/**
 * Class m210224_044440_add_colum_to_client_salary
 */
class m210224_044440_add_colum_to_client_salary extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('client_salary', 'order_id', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210224_044440_add_colum_to_client_salary cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210224_044440_add_colum_to_client_salary cannot be reverted.\n";

        return false;
    }
    */
}
