<?php

use yii\db\Migration;

/**
 * Class m210215_090359_delete_column_team_user_id
 */
class m210215_090359_delete_column_team_user_id extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = "ALTER TABLE team DROP COLUMN user_id;";
        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210215_090359_delete_column_team_user_id cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210215_090359_delete_column_team_user_id cannot be reverted.\n";

        return false;
    }
    */
}
