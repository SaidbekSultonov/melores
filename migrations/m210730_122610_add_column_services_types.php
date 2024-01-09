<?php

use yii\db\Migration;

/**
 * Class m210730_122610_add_column_services_types
 */
class m210730_122610_add_column_services_types extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = "ALTER TABLE services_types ADD COLUMN status SMALLINT";
        $this->execute($sql);

        $sql = "ALTER TABLE trade_services_types ADD COLUMN status SMALLINT";
        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210730_122610_add_column_services_types cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210730_122610_add_column_services_types cannot be reverted.\n";

        return false;
    }
    */
}
