<?php

use yii\db\Migration;

/**
 * Class m210722_053308_edit_bot_tables
 */
class m210722_053308_edit_bot_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('company_files', 'file_id', 'varchar');
        $this->alterColumn('trade_company_files', 'file_id', 'varchar');

        $this->dropColumn('services', 'title');
        $this->addColumn('services', 'title_uz', $this->string());
        $this->addColumn('services', 'title_ru', $this->string());

        $this->dropColumn('services_types', 'title');
        $this->addColumn('services_types', 'title_uz', $this->string());
        $this->addColumn('services_types', 'title_ru', $this->string());

        $this->dropColumn('district', 'title');
        $this->addColumn('district', 'title_uz', $this->string());
        $this->addColumn('district', 'title_ru', $this->string());

        $this->dropColumn('company', 'title');
        $this->addColumn('company', 'title_uz', $this->string());
        $this->addColumn('company', 'title_ru', $this->string());

        $this->dropColumn('company_files', 'title');
        $this->addColumn('company_files', 'title_uz', $this->string());
        $this->addColumn('company_files', 'title_ru', $this->string());

        $this->dropColumn('trade_services', 'title');
        $this->addColumn('trade_services', 'title_uz', $this->string());
        $this->addColumn('trade_services', 'title_ru', $this->string());

        $this->dropColumn('trade_services_types', 'title');
        $this->addColumn('trade_services_types', 'title_uz', $this->string());
        $this->addColumn('trade_services_types', 'title_ru', $this->string());

        $this->dropColumn('trade_district', 'title');
        $this->addColumn('trade_district', 'title_uz', $this->string());
        $this->addColumn('trade_district', 'title_ru', $this->string());

        $this->dropColumn('trade_company', 'title');
        $this->addColumn('trade_company', 'title_uz', $this->string());
        $this->addColumn('trade_company', 'title_ru', $this->string());

        $this->dropColumn('trade_company_files', 'title');
        $this->addColumn('trade_company_files', 'title_uz', $this->string());
        $this->addColumn('trade_company_files', 'title_ru', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210722_053308_edit_bot_tables cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210722_053308_edit_bot_tables cannot be reverted.\n";

        return false;
    }
    */
}
