<?php

use yii\db\Migration;

/**
 * Class m210318_140942_send_video_table
 */
class m210318_140942_send_video_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('send_video', [
            'id' => $this->primaryKey(),
            'first_name' => $this->string(),
            'user_name' => $this->string(),
            'video_id' => $this->integer()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210318_140942_send_video_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210318_140942_send_video_table cannot be reverted.\n";

        return false;
    }
    */
}
