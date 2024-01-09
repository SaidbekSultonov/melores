<?php

use yii\db\Migration;

/**
 * Class m210301_091834_add_column_video_last_id
 */
class m210301_091834_add_column_video_last_id extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = "ALTER TABLE last_id ADD COLUMN video_last_id integer;";
        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210301_091834_add_column_video_last_id cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210301_091834_add_column_video_last_id cannot be reverted.\n";

        return false;
    }
    */
}
