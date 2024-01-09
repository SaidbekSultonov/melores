<?php

use yii\db\Migration;

/**
 * Class m210301_075445_create_table_video_log
 */
class m210301_075445_create_table_video_log extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = "
        CREATE TABLE IF NOT EXISTS public.video_log
        (
            id serial NOT NULL,
            chat_id INTEGER,
            file_id CHARACTER VARYING(300),
            video_id INTEGER,
            type CHARACTER VARYING(50),
            PRIMARY KEY (id)
        );";
        
        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210301_075445_create_table_video_log cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210301_075445_create_table_video_log cannot be reverted.\n";

        return false;
    }
    */
}
