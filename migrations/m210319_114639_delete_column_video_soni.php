<?php

use yii\db\Migration;

/**
 * Class m210319_114639_delete_column_video_soni
 */
class m210319_114639_delete_column_video_soni extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('video', 'video_number');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210319_114639_delete_column_video_soni cannot be reverted.\n";

        return false;
    }
    */
}
