<?php

use yii\db\Migration;

/**
 * Class m210327_094210_newLongLAt
 */
class m210327_094210_newLongLAt extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = "ALTER TABLE order_materials ADD COLUMN long CHARACTER VARYING";
        $this->execute($sql);
        $sql = "ALTER TABLE order_materials ADD COLUMN lat CHARACTER VARYING";
        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210327_094210_newLongLAt cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210327_094210_newLongLAt cannot be reverted.\n";

        return false;
    }
    */
}
