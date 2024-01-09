<?php

use yii\db\Migration;

/**
 * Class m210304_102423_add_column_password_to_users
 */
class m210304_102423_add_column_password_to_users extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = "ALTER TABLE users ADD COLUMN password CHARACTER VARYING(255);";
        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210304_102423_add_column_password_to_users cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210304_102423_add_column_password_to_users cannot be reverted.\n";

        return false;
    }
    */
}
