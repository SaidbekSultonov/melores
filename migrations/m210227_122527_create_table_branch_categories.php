<?php

use yii\db\Migration;

/**
 * Class m210227_122527_create_table_branch_categories
 */
class m210227_122527_create_table_branch_categories extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = "
        CREATE TABLE IF NOT EXISTS public.branch_categories
        (
            id serial NOT NULL,
            branch_id INTEGER,
            category_id INTEGER,
            status SMALLINT,
            PRIMARY KEY (id)
        );";
        
        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210227_122527_create_table_branch_categories cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210227_122527_create_table_branch_categories cannot be reverted.\n";

        return false;
    }
    */
}
