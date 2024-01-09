<?php

use yii\db\Migration;

/**
 * Class m210824_153051_add_column_send_video
 */
class m210824_153051_add_column_send_video extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = "ALTER TABLE send_video ADD COLUMN is_send integer default 0;";
        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210824_153051_add_column_send_video cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210824_153051_add_column_send_video cannot be reverted.\n";

        return false;
    }
    */
}
