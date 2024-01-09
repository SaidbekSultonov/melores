<?php

use yii\db\Migration;

/**
 * Class m220120_115452_response_person_and_brigada
 */
class m220120_115452_response_person_and_brigada extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('order_responsibles', [
            'id' => $this->primaryKey(),
            'order_id' => $this->integer(),
            'user_id' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('order_responsibles');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220120_115452_response_person_and_brigada cannot be reverted.\n";

        return false;
    }
    */
}
