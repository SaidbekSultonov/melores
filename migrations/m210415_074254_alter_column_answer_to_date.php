<?php

use yii\db\Migration;

/**
 * Class m210415_074254_alter_column_answer_to_date
 */
class m210415_074254_alter_column_answer_to_date extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        
        $sql = "ALTER TABLE answer
        ALTER COLUMN date TYPE DATE";
        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210415_074254_alter_column_answer_to_date cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210415_074254_alter_column_answer_to_date cannot be reverted.\n";

        return false;
    }
    */
}
