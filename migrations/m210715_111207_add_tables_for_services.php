<?php

use yii\db\Migration;

/**
 * Class m210715_111207_add_tables_for_services
 */
class m210715_111207_add_tables_for_services extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = '
            CREATE TABLE IF NOT EXISTS "services" (
                "id" serial NOT NULL,
                "title" CHARACTER VARYING,
                "type" SMALLINT,
                PRIMARY KEY ("id")
            );
        ';
        $this->execute($sql);

        $sql = '
            CREATE TABLE IF NOT EXISTS "services_types" (
                "id" serial NOT NULL,
                "title" CHARACTER VARYING,
                "services_id" INTEGER,
                PRIMARY KEY ("id")
            );
        ';
        $this->execute($sql);

        $sql = '
            CREATE TABLE IF NOT EXISTS "district" (
                "id" serial NOT NULL,
                "title" CHARACTER VARYING,
                "status" SMALLINT,
                PRIMARY KEY ("id")
            );
        ';
        $this->execute($sql);

        $sql = '
            CREATE TABLE IF NOT EXISTS "company" (
                "id" serial NOT NULL,
                "title" CHARACTER VARYING,
                "district_id" INTEGER,
                "services_type_id" INTEGER,
                PRIMARY KEY ("id")
            );
        ';
        $this->execute($sql);

        $sql = '
            CREATE TABLE IF NOT EXISTS "company_files" (
                "id" serial NOT NULL,
                "company_id" INTEGER,
                "file_id" INTEGER,
                PRIMARY KEY ("id")
            );
        ';
        $this->execute($sql);

        $sql = '
            CREATE TABLE IF NOT EXISTS "files" (
                "id" serial NOT NULL,
                "title" CHARACTER VARYING,
                "type" SMALLINT,
                "status" SMALLINT,
                PRIMARY KEY ("id")
            );
        ';
        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210715_111207_add_tables_for_services cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210715_111207_add_tables_for_services cannot be reverted.\n";

        return false;
    }
    */
}
