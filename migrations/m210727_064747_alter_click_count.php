<?php

use yii\db\Migration;

/**
 * Class m210727_064747_alter_click_count
 */
class m210727_064747_alter_click_count extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = "ALTER TABLE services ALTER COLUMN click_count SET DEFAULT 0;";
        $this->execute($sql);
        $sql = "ALTER TABLE trade_services ALTER COLUMN click_count SET DEFAULT 0;";
        $this->execute($sql);
        $sql = "ALTER TABLE services_types ALTER COLUMN click_count SET DEFAULT 0;";
        $this->execute($sql);
        $sql = "ALTER TABLE trade_services_types ALTER COLUMN click_count SET DEFAULT 0;";
        $this->execute($sql);
        $sql = "ALTER TABLE district ALTER COLUMN click_count SET DEFAULT 0;";
        $this->execute($sql);
        $sql = "ALTER TABLE trade_district ALTER COLUMN click_count SET DEFAULT 0;";
        $this->execute($sql);
        $sql = "ALTER TABLE company ALTER COLUMN click_count SET DEFAULT 0;";
        $this->execute($sql);
        $sql = "ALTER TABLE trade_company ALTER COLUMN click_count SET DEFAULT 0;";
        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210727_064747_alter_click_count cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210727_064747_alter_click_count cannot be reverted.\n";

        return false;
    }
    */
}
