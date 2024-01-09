<?php

use yii\db\Migration;

/**
 * Class m210210_104840_add_column_to_users_bot_id
 */
class m210210_104840_add_column_to_users_bot_id extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = "ALTER TABLE users ADD COLUMN bot_id integer;";
        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210210_104840_add_column_to_users_bot_id cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210210_104840_add_column_to_users_bot_id cannot be reverted.\n";

        return false;
    }
    */
}
