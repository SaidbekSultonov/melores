<?php

use yii\db\Migration;

/**
 * Class m210223_121714_video_check_video_table
 */
class m210223_121714_video_check_video_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('video', [
            'id' => $this->primaryKey(),
            'file_id' => $this->string(),
            'video_number' => $this->integer(),
            'status' => $this->smallInteger()
        ]);

        $this->createTable('check_video', [
            'id' => $this->primaryKey(),
            'chat_id' => $this->integer(),
            'video_number' => $this->integer(),
            'send_date' => $this->timestamp(),
            'next_send_date' => $this->timestamp(),
            'send' => $this->smallInteger(),
            'status' => $this->smallInteger()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210223_121714_video_check_video_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210223_121714_video_check_video_table cannot be reverted.\n";

        return false;
    }
    */
}
