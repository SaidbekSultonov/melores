<?php

use yii\db\Migration;

/**
 * Class m210517_071852_clients_step_add_row_clients_id
 */
class m210517_071852_clients_step_add_row_clients_id extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('client_step', 'client_id', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210517_071852_clients_step_add_row_clients_id cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210517_071852_clients_step_add_row_clients_id cannot be reverted.\n";

        return false;
    }
    */
}
