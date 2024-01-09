<?php

use yii\db\Migration;

/**
 * Class m220205_112552_change_chat_id_column
 */
class m220205_112552_change_chat_id_column extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = "ALTER TABLE step_order ALTER COLUMN chat_id TYPE bigint";
        $this->execute($sql);
        $sql = "ALTER TABLE user_balls ALTER COLUMN chat_id TYPE bigint";
        $this->execute($sql);
        $sql = "ALTER TABLE trade_users ALTER COLUMN chat_id TYPE bigint";
        $this->execute($sql);
        $sql = "ALTER TABLE trade_step ALTER COLUMN chat_id TYPE bigint";
        $this->execute($sql);
        $sql = "ALTER TABLE trade_service_statistics ALTER COLUMN chat_id TYPE bigint";
        $this->execute($sql);
        $sql = "ALTER TABLE trade_message_id ALTER COLUMN chat_id TYPE bigint";
        $this->execute($sql);
        $sql = "ALTER TABLE trade_log ALTER COLUMN chat_id TYPE bigint";
        $this->execute($sql);
        $sql = "ALTER TABLE trade_last_id ALTER COLUMN chat_id TYPE bigint";
        $this->execute($sql);
        $sql = "ALTER TABLE task_step ALTER COLUMN chat_id TYPE bigint";
        $this->execute($sql);
        $sql = "ALTER TABLE task_message_id ALTER COLUMN chat_id TYPE bigint";
        $this->execute($sql);
        $sql = "ALTER TABLE task_last_id ALTER COLUMN chat_id TYPE bigint";
        $this->execute($sql);
        $sql = "ALTER TABLE tasks ALTER COLUMN admin_id TYPE bigint";
        $this->execute($sql);
        $sql = "ALTER TABLE step ALTER COLUMN chat_id TYPE bigint";
        $this->execute($sql);
        $sql = "ALTER TABLE service_users ALTER COLUMN chat_id TYPE bigint";
        $this->execute($sql);
        $sql = "ALTER TABLE service_step ALTER COLUMN chat_id TYPE bigint";
        $this->execute($sql);
        $sql = "ALTER TABLE service_statistics ALTER COLUMN chat_id TYPE bigint";
        $this->execute($sql);
        $sql = "ALTER TABLE service_message_id ALTER COLUMN chat_id TYPE bigint";
        $this->execute($sql);
        $sql = "ALTER TABLE service_last_id ALTER COLUMN chat_id TYPE bigint";
        $this->execute($sql);
        $sql = "ALTER TABLE send_video ALTER COLUMN chat_id TYPE bigint";
        $this->execute($sql);
        $sql = "ALTER TABLE sale_users ALTER COLUMN chat_id TYPE bigint";
        $this->execute($sql);
        $sql = "ALTER TABLE sale_step ALTER COLUMN chat_id TYPE bigint";
        $this->execute($sql);
        $sql = "ALTER TABLE sale_service_statistics ALTER COLUMN chat_id TYPE bigint";
        $this->execute($sql);
        $sql = "ALTER TABLE sale_message_id ALTER COLUMN chat_id TYPE bigint";
        $this->execute($sql);
        $sql = "ALTER TABLE sale_last_id ALTER COLUMN chat_id TYPE bigint";
        $this->execute($sql);
        $sql = "ALTER TABLE salary_user_balance ALTER COLUMN user_id TYPE bigint";
        $this->execute($sql);
        $sql = "ALTER TABLE salary_step ALTER COLUMN chat_id TYPE bigint";
        $this->execute($sql);
        $sql = "ALTER TABLE salary_message_id ALTER COLUMN chat_id TYPE bigint";
        $this->execute($sql);
        $sql = "ALTER TABLE salary_last_id ALTER COLUMN chat_id TYPE bigint";
        $this->execute($sql);
        $sql = "ALTER TABLE salary_amount ALTER COLUMN chat_id TYPE bigint";
        $this->execute($sql);
        $sql = "ALTER TABLE quiz_step ALTER COLUMN chat_id TYPE bigint";
        $this->execute($sql);
        $sql = "ALTER TABLE orginal_step ALTER COLUMN chat_id TYPE bigint";
        $this->execute($sql);
        $sql = "ALTER TABLE orginal_message_id ALTER COLUMN chat_id TYPE bigint";
        $this->execute($sql);
        $sql = "ALTER TABLE orginal_last_id ALTER COLUMN chat_id TYPE bigint";
        $this->execute($sql);
        $sql = "ALTER TABLE last_id_order ALTER COLUMN chat_id TYPE bigint";
        $this->execute($sql);
        $sql = "ALTER TABLE last_id ALTER COLUMN chat_id TYPE bigint";
        $this->execute($sql);
        $sql = "ALTER TABLE delete_messages ALTER COLUMN chat_id TYPE bigint";
        $this->execute($sql);
        $sql = "ALTER TABLE client_step ALTER COLUMN chat_id TYPE bigint";
        $this->execute($sql);
        $sql = "ALTER TABLE client_salary ALTER COLUMN chat_id TYPE bigint";
        $this->execute($sql);
        $sql = "ALTER TABLE client_last_id ALTER COLUMN chat_id TYPE bigint";
        $this->execute($sql);
        $sql = "ALTER TABLE client_balls ALTER COLUMN chat_id TYPE bigint";
        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m220205_112552_change_chat_id_column cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220205_112552_change_chat_id_column cannot be reverted.\n";

        return false;
    }
    */
}
