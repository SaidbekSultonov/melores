<?php

use yii\db\Migration;

/**
 * Class m210319_130811_drop_table_video_log
 */
class m210319_130811_drop_table_video_log extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropTable('video_log');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210319_130811_drop_table_video_log cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210319_130811_drop_table_video_log cannot be reverted.\n";

        return false;
    }
    */
}
