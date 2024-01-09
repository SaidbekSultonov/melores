<?php

use yii\db\Migration;

/**
 * Class m210722_064923_new_tables_for_usta_bot
 */
class m210722_064923_new_tables_for_usta_bot extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('company', 'district_id');
        $this->dropColumn('company', 'services_type_id');
        $this->dropColumn('trade_company', 'district_id');
        $this->dropColumn('trade_company', 'services_type_id');

        $this->createTable('company_district', [
            'id' => $this->primaryKey(),
            'company_id' => $this->integer(),
            'district_id' => $this->integer(),
        ]);

        $this->createTable('company_services_type', [
            'id' => $this->primaryKey(),
            'company_id' => $this->integer(),
            'services_type_id' => $this->integer(),
        ]);

        $this->createTable('trade_company_services_type', [
            'id' => $this->primaryKey(),
            'company_id' => $this->integer(),
            'services_type_id' => $this->integer(),
        ]);

        $this->createTable('trade_company_district', [
            'id' => $this->primaryKey(),
            'company_id' => $this->integer(),
            'district_id' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210722_064923_new_tables_for_usta_bot cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210722_064923_new_tables_for_usta_bot cannot be reverted.\n";

        return false;
    }
    */
}
