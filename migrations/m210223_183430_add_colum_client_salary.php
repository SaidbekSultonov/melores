<?php

use yii\db\Migration;

/**
 * Class m210223_183430_add_colum_client_salary
 */
class m210223_183430_add_colum_client_salary extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('client_salary', [
            'id' => $this->primaryKey(),
            'chat_id' => $this->integer(),
            'quantity' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210223_183430_add_colum_client_salary cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210223_183430_add_colum_client_salary cannot be reverted.\n";

        return false;
    }
    */
}
