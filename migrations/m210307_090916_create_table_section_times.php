<?php

use yii\db\Migration;

/**
 * Class m210307_090916_create_table_section_times
 */
class m210307_090916_create_table_section_times extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = "
        CREATE TABLE IF NOT EXISTS public.section_times
        (
            id serial NOT NULL,
            section_id INTEGER,
            work_time INTEGER,
            start_date DATE,
            end_date DATE,
            status SMALLINT DEFAULT 1,
            PRIMARY KEY (id)
        );";

        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210307_090916_create_table_section_times cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210307_090916_create_table_section_times cannot be reverted.\n";

        return false;
    }
    */
}
