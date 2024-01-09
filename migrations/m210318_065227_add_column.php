<?php

use yii\db\Migration;

/**
 * Class m210318_065227_add_column
 */
class m210318_065227_add_column extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = "ALTER TABLE sections ADD COLUMN order_column SMALLINT";
        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210318_065227_add_column cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210318_065227_add_column cannot be reverted.\n";

        return false;
    }
    */
}
