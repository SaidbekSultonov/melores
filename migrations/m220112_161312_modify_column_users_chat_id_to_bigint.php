<?php

use yii\db\Migration;

/**
 * Class m220112_161312_modify_column_users_chat_id_to_bigint
 */
class m220112_161312_modify_column_users_chat_id_to_bigint extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = "ALTER TABLE users ALTER COLUMN chat_id TYPE BIGINT;";
        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m220112_161312_modify_column_users_chat_id_to_bigint cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220112_161312_modify_column_users_chat_id_to_bigint cannot be reverted.\n";

        return false;
    }
    */
}
