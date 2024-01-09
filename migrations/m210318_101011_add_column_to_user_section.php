<?php

use yii\db\Migration;

/**
 * Class m210318_101011_add_column_to_user_section
 */
class m210318_101011_add_column_to_user_section extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = "ALTER TABLE users_section ADD COLUMN role SMALLINT";
        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210318_101011_add_column_to_user_section cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210318_101011_add_column_to_user_section cannot be reverted.\n";

        return false;
    }
    */
}
