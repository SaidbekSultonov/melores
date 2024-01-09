<?php

use yii\db\Migration;

/**
 * Class m210722_070112_add_cloumn_bot
 */
class m210722_070112_add_cloumn_bot extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('company_files', 'services_type_id', $this->integer());
        $this->addColumn('company_files', 'district_id', $this->integer());
        $this->addColumn('trade_company_files', 'services_type_id', $this->integer());
        $this->addColumn('trade_company_files', 'district_id', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210722_070112_add_cloumn_bot cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210722_070112_add_cloumn_bot cannot be reverted.\n";

        return false;
    }
    */
}
