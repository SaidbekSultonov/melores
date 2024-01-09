<?php

use yii\db\Migration;

/**
 * Class m210218_155551_add_table_user_sections
 */
class m210218_155551_add_table_user_sections extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = "
        CREATE TABLE IF NOT EXISTS public.users_section
        (
            id serial NOT NULL,
            user_id INTEGER,
            section_id INTEGER,
            PRIMARY KEY (id)
        );";

        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210218_155551_add_table_user_sections cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210218_155551_add_table_user_sections cannot be reverted.\n";

        return false;
    }
    */
}
