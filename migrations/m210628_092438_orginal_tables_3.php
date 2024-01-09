<?php

use yii\db\Migration;

/**
 * Class m210628_092438_orginal_tables_3
 */
class m210628_092438_orginal_tables_3 extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = '
                CREATE TABLE IF NOT EXISTS "orginal_step_resurs" (
                    "id" serial NOT NULL,
                    "file_id" TEXT NULL DEFAULT NULL,
                    "type" TEXT NULL DEFAULT NULL,
                    "lang" INTEGER NULL DEFAULT NULL,
                    "category" INTEGER NULL DEFAULT NULL,
                    "caption" TEXT NULL DEFAULT NULL,
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
        echo "m210628_092438_orginal_tables_3 cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210628_092438_orginal_tables_3 cannot be reverted.\n";

        return false;
    }
    */
}
