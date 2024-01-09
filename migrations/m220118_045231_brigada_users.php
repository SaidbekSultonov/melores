<?php

use yii\db\Migration;

/**
 * Class m220118_045231_brigada_users
 */
class m220118_045231_brigada_users extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('brigada_users', [
            'id' => $this->primaryKey(),
            'brigada_id' => $this->integer(),
            'user_id' => $this->integer(),
        ]);

        $this->createTable('brigada_leader', [
            'id' => $this->primaryKey(),
            'brigada_id' => $this->integer(),
            'user_id' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('brigada_users');
        $this->dropTable('brigada_leader');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220118_045231_brigada_users cannot be reverted.\n";

        return false;
    }
    */
}
