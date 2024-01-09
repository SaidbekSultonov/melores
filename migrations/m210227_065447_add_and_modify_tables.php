<?php

use yii\db\Migration;

/**
 * Class m210227_065447_add_and_modify_tables
 */
class m210227_065447_add_and_modify_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = "ALTER TABLE video ADD COLUMN caption text;";
        $this->execute($sql);

        $sql = "ALTER TABLE client_recommendation ALTER COLUMN full_name TYPE CHARACTER VARYING(255);";
        $this->execute($sql);

        $sql = "ALTER TABLE feedback_client ALTER COLUMN title TYPE TEXT;";
        $this->execute($sql);

        $sql = "ALTER TABLE feedback_user ALTER COLUMN title TYPE TEXT;";
        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210227_065447_add_and_modify_tables cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210227_065447_add_and_modify_tables cannot be reverted.\n";

        return false;
    }
    */
}
