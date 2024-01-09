<?php

use yii\db\Migration;

/**
 * Class m210721_074930_alter_bot_tables
 */
class m210721_074930_alter_bot_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('company_files', 'title', $this->string());
        $this->addColumn('company_files', 'type', $this->string());
        $this->addColumn('company_files', 'status', $this->smallInteger());
        $this->dropTable('files');

        $this->createTable('trade_message_id', [
            'id' => $this->primaryKey(),
            'chat_id' => $this->integer(),
            'message_id' => $this->integer(),
        ]);

        $this->createTable('trade_users', [
            'id' => $this->primaryKey(),
            'chat_id' => $this->integer(),
            'username' => $this->string(),
            'phone_number' => $this->string(),
        ]);

        $this->createTable('trade_step', [
            'id' => $this->primaryKey(),
            'chat_id' => $this->integer(),
            'step_1' => $this->smallInteger(),
            'step_2' => $this->smallInteger(),
        ]);

        $sql = '
            CREATE TABLE IF NOT EXISTS "trade_services" (
                "id" serial NOT NULL,
                "title" CHARACTER VARYING,
                "type" SMALLINT,
                PRIMARY KEY ("id")
            );
        ';
        $this->execute($sql);

        $sql = '
            CREATE TABLE IF NOT EXISTS "trade_services_types" (
                "id" serial NOT NULL,
                "title" CHARACTER VARYING,
                "services_id" INTEGER,
                PRIMARY KEY ("id")
            );
        ';
        $this->execute($sql);

        $sql = '
            CREATE TABLE IF NOT EXISTS "trade_district" (
                "id" serial NOT NULL,
                "title" CHARACTER VARYING,
                "status" SMALLINT,
                PRIMARY KEY ("id")
            );
        ';
        $this->execute($sql);

        $sql = '
            CREATE TABLE IF NOT EXISTS "trade_company" (
                "id" serial NOT NULL,
                "title" CHARACTER VARYING,
                "district_id" INTEGER,
                "services_type_id" INTEGER,
                PRIMARY KEY ("id")
            );
        ';
        $this->execute($sql);

        $sql = '
            CREATE TABLE IF NOT EXISTS "trade_company_files" (
                "id" serial NOT NULL,
                "company_id" INTEGER,
                "file_id" INTEGER,
                "title" CHARACTER VARYING,
                "type" CHARACTER VARYING,
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
        echo "m210721_074930_alter_bot_tables cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210721_074930_alter_bot_tables cannot be reverted.\n";

        return false;
    }
    */
}
