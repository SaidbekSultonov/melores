<?php

use yii\db\Migration;

/**
 * Class m210422_061615_edit_column_task
 */
class m210422_061615_edit_column_task extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = "ALTER TABLE tasks DROP COLUMN dead_line;";
        $this->execute($sql);

        $sql = "ALTER TABLE tasks ADD COLUMN dead_line timestamp;";
        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210422_061615_edit_column_task cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210422_061615_edit_column_task cannot be reverted.\n";

        return false;
    }
    */
}
