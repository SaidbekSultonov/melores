<?php

use yii\db\Migration;

/**
 * Class m210219_084534_modify_column_bot
 */
class m210219_084534_modify_column_bot extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = "ALTER TABLE bot ALTER COLUMN username TYPE CHARACTER VARYING;";
        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210219_084534_modify_column_bot cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210219_084534_modify_column_bot cannot be reverted.\n";

        return false;
    }
    */
}
