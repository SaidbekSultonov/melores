<?php

use yii\db\Migration;

/**
 * Class m210723_153446_add_column_to_company
 */
class m210723_153446_add_column_to_company extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = "ALTER TABLE company ADD COLUMN order_column SMALLINT";
        $this->execute($sql);
        $sql = "ALTER TABLE services ADD COLUMN order_column SMALLINT";
        $this->execute($sql);
        $sql = "ALTER TABLE services_types ADD COLUMN order_column SMALLINT";
        $this->execute($sql);

        $sql = "ALTER TABLE trade_company ADD COLUMN order_column SMALLINT";
        $this->execute($sql);
        $sql = "ALTER TABLE trade_services ADD COLUMN order_column SMALLINT";
        $this->execute($sql);
        $sql = "ALTER TABLE trade_services_types ADD COLUMN order_column SMALLINT";
        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210723_153446_add_column_to_company cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210723_153446_add_column_to_company cannot be reverted.\n";

        return false;
    }
    */
}
