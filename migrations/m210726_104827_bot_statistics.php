<?php

use yii\db\Migration;

/**
 * Class m210726_104827_bot_statistics
 */
class m210726_104827_bot_statistics extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('services', 'click_count', $this->integer());
        $this->addColumn('trade_services', 'click_count', $this->integer());
        $this->addColumn('services_types', 'click_count', $this->integer());
        $this->addColumn('trade_services_types', 'click_count', $this->integer());
        $this->addColumn('district', 'click_count', $this->integer());
        $this->addColumn('trade_district', 'click_count', $this->integer());
        $this->addColumn('company', 'click_count', $this->integer());
        $this->addColumn('trade_company', 'click_count', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210726_104827_bot_statistics cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210726_104827_bot_statistics cannot be reverted.\n";

        return false;
    }
    */
}
