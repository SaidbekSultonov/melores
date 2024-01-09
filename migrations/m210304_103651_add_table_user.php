<?php

use yii\db\Migration;

/**
 * Class m210304_103651_add_table_user
 */
class m210304_103651_add_table_user extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $sql = "
        CREATE TABLE IF NOT EXISTS public.user
        (
            id serial NOT NULL,
            username character varying(100) NOT NULL,
            password character varying(255) NOT NULL,
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
        echo "m210304_103651_add_table_user cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210304_103651_add_table_user cannot be reverted.\n";

        return false;
    }
    */
}
