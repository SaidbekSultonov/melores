<?php

use yii\db\Migration;

/**
 * Class m210817_085715_sale_bot_default_0
 */
class m210817_085715_sale_bot_default_0 extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = "ALTER TABLE sale_services_type ALTER COLUMN status SET DEFAULT 1;";
        $this->execute($sql);

        $sql = "ALTER TABLE sale_services_type ALTER COLUMN click_count SET DEFAULT 0;";
        $this->execute($sql);

        $sql = "ALTER TABLE sale_company ALTER COLUMN click_count SET DEFAULT 0;";
        $this->execute($sql);

        $sql = "ALTER TABLE sale_district ALTER COLUMN click_count SET DEFAULT 0;";
        $this->execute($sql);

        $sql = "ALTER TABLE sale_services ALTER COLUMN click_count SET DEFAULT 0;";
        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210817_085715_sale_bot_default_0 cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210817_085715_sale_bot_default_0 cannot be reverted.\n";

        return false;
    }
    */
}
