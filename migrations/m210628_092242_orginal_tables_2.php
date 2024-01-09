<?php

use yii\db\Migration;

/**
 * Class m210628_092242_orginal_tables_2
 */
class m210628_092242_orginal_tables_2 extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = '

        CREATE TABLE IF NOT EXISTS "orginal_lon" (
            "id" serial NOT NULL  ,
            "lang" INTEGER NULL DEFAULT NULL,
            "title" INTEGER NULL DEFAULT NULL,
            PRIMARY KEY ("id")
        );
       ';
        $this->execute($sql);

        $sql_3 = '

        CREATE TABLE IF NOT EXISTS "orginal_step" (
            "id" serial NOT NULL  ,
            "chat_id" INTEGER NULL DEFAULT NULL,
            "admin" INTEGER NULL DEFAULT 0,
            "name" VARCHAR(255) NULL DEFAULT NULL::character varying,
            "username" VARCHAR(255) NULL DEFAULT NULL::character varying,
            "step_1" INTEGER NULL DEFAULT 0,
            "step_2" INTEGER NULL DEFAULT 0,
            PRIMARY KEY ("id")
        );
       ';
        $this->execute($sql_3);



        $sql_5 = '

        CREATE TABLE IF NOT EXISTS "orginal_step_katalog" (
	"id" serial NOT NULL,
	"lang" INTEGER NULL DEFAULT NULL,
	"title" VARCHAR(255) NULL DEFAULT NULL::character varying,
	PRIMARY KEY ("id")
);
       ';
        $this->execute($sql_5);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210628_092242_orginal_tables_2 cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210628_092242_orginal_tables_2 cannot be reverted.\n";

        return false;
    }
    */
}
