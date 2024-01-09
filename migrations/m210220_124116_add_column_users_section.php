<?php

use yii\db\Migration;

/**
 * Class m210220_124116_add_column_users_section
 */
class m210220_124116_add_column_users_section extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = "ALTER TABLE users_section ADD COLUMN bot_users_id integer;";
        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210220_124116_add_column_users_section cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210220_124116_add_column_users_section cannot be reverted.\n";

        return false;
    }
    */
}
