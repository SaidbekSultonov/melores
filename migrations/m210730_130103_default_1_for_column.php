<?php

use yii\db\Migration;

/**
 * Class m210730_130103_default_1_for_column
 */
class m210730_130103_default_1_for_column extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = "ALTER TABLE services_types ALTER COLUMN status SET DEFAULT 1;";
        $this->execute($sql);

        $sql = "ALTER TABLE trade_services_types ALTER COLUMN status SET DEFAULT 1;";
        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210730_130103_default_1_for_column cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210730_130103_default_1_for_column cannot be reverted.\n";

        return false;
    }
    */
}
