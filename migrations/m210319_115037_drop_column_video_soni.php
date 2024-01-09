<?php

use yii\db\Migration;

/**
 * Class m210319_115037_drop_column_video_soni
 */
class m210319_115037_drop_column_video_soni extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('video', 'video_number');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210319_115037_drop_column_video_soni cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210319_115037_drop_column_video_soni cannot be reverted.\n";

        return false;
    }
    */
}
