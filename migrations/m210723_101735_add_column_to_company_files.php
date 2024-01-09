<?php

use yii\db\Migration;

/**
 * Class m210723_101735_add_column_to_company_files
 */
class m210723_101735_add_column_to_company_files extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = "ALTER TABLE company_files ADD COLUMN long DOUBLE PRECISION";
        $this->execute($sql);
        $sql = "ALTER TABLE company_files ADD COLUMN lat DOUBLE PRECISION";
        $this->execute($sql);
        $sql = "ALTER TABLE trade_company_files ADD COLUMN long DOUBLE PRECISION";
        $this->execute($sql);
        $sql = "ALTER TABLE trade_company_files ADD COLUMN lat DOUBLE PRECISION";
        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210723_101735_add_column_to_company_files cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210723_101735_add_column_to_company_files cannot be reverted.\n";

        return false;
    }
    */
}
