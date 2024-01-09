<?php

use yii\db\Migration;

/**
 * Handles the dropping of table `{{%and_new}}`.
 */
class m230606_015019_drop_and_new_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropTable('orginal_step');
        
        $sql = "
        CREATE TABLE IF NOT EXISTS public.orginal_step
        (
            id serial NOT NULL,
            chat_id bigint,
            admin integer DEFAULT 0,
            name character varying(255) DEFAULT NULL::character varying,
            username character varying(255) DEFAULT NULL::character varying,
            step_1 integer DEFAULT 0,
            step_2 integer DEFAULT 0,
            status integer DEFAULT 0,
            is_bot integer DEFAULT 0,
            PRIMARY KEY (id)
        );";

        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->createTable('{{%and_new}}', [
            'id' => $this->primaryKey(),
        ]);
    }
}
