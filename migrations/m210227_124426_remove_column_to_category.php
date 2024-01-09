<?php

use yii\db\Migration;

/**
 * Class m210227_124426_remove_column_to_category
 */
class m210227_124426_remove_column_to_category extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = "ALTER TABLE category DROP COLUMN branch_id;";
        $this->execute($sql);
    }   

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210227_124426_remove_column_to_category cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210227_124426_remove_column_to_category cannot be reverted.\n";

        return false;
    }
    */
}
