<?php

use yii\db\Migration;

/**
 * Class m210628_091259_orginal_tables
 */
class m210628_091259_orginal_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = '
            CREATE TABLE IF NOT EXISTS "orginal_katalog" (
                "id" serial NOT NULL,
                "file_id" TEXT NULL DEFAULT NULL,
                "caption" TEXT NULL DEFAULT NULL,
                "lang" INTEGER NULL DEFAULT NULL,
                "category" INTEGER NULL DEFAULT NULL,
                "type" VARCHAR(255) NULL DEFAULT NULL::character varying,
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
        echo "m210628_091259_orginal_tables cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210628_091259_orginal_tables cannot be reverted.\n";

        return false;
    }
    */
}
