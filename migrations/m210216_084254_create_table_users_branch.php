<?php

use yii\db\Migration;

/**
 * Class m210216_084254_create_table_users_branch
 */
class m210216_084254_create_table_users_branch extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = "
        CREATE TABLE IF NOT EXISTS public.users_branch
        (
            id serial NOT NULL,
            user_id INTEGER,
            branch_id INTEGER,
            PRIMARY KEY (id)
        );";

        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210216_084254_create_table_users_branch cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210216_084254_create_table_users_branch cannot be reverted.\n";

        return false;
    }
    */
}
