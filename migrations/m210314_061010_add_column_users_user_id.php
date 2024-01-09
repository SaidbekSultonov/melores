<?php

use yii\db\Migration;

/**
 * Class m210314_061010_add_column_users_user_id
 */
class m210314_061010_add_column_users_user_id extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = "ALTER TABLE users ADD COLUMN user_id integer;";
        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210314_061010_add_column_users_user_id cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210314_061010_add_column_users_user_id cannot be reverted.\n";

        return false;
    }
    */
}
