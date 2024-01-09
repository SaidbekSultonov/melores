<?php

use yii\db\Migration;

/**
 * Class m220116_151724_modify_column_chat_id_from_otdel_log_message_bigint
 */
class m220116_151724_modify_column_chat_id_from_otdel_log_message_bigint extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = "ALTER TABLE otdel_log_message ALTER COLUMN chat_id TYPE BIGINT;";
        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m220116_151724_modify_column_chat_id_from_otdel_log_message_bigint cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220116_151724_modify_column_chat_id_from_otdel_log_message_bigint cannot be reverted.\n";

        return false;
    }
    */
}
