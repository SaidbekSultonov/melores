<?php

use yii\db\Migration;

/**
 * Class m210816_190329_sale_tables
 */
class m210816_190329_sale_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('sale_company', [
            'id' => $this->primaryKey(),
            'title_uz' => $this->string(),
            'title_ru' => $this->string(),
            'order_column' => $this->smallInteger(),
            'click_count' => $this->integer(),
        ]);

        $this->createTable('sale_company_files', [
            'id' => $this->primaryKey(),
            'company_id' => $this->integer(),
            'file_id' => $this->string(),
            'type' => $this->string(),
            'status' => $this->smallInteger(),
            'title_uz' => $this->string(),
            'title_ru' => $this->string(),
            'services_type_id' => $this->integer(),
            'district_id' => $this->integer(),
            'long' => $this->double(),
            'lat' => $this->double()
        ]);

        $this->createTable('sale_company_services_type', [
            'id' => $this->primaryKey(),
            'company_id' => $this->integer(),
            'services_type_id' => $this->integer()
        ]);

        $this->createTable('sale_district', [
            'id' => $this->primaryKey(),
            'status' => $this->smallInteger(),
            'title_uz' => $this->string(),
            'title_ru' => $this->string(),
            'click_count' => $this->integer()
        ]);

        $this->createTable('sale_message_id', [
            'id' => $this->primaryKey(),
            'chat_id' => $this->integer(),
            'message_id' => $this->integer()
        ]);

        $this->createTable('sale_services', [
            'id' => $this->primaryKey(),
            'type' => $this->smallInteger(),
            'title_uz' => $this->string(),
            'title_ru' => $this->string(),
            'order_column' => $this->smallInteger(),
            'click_count' => $this->integer()
        ]);

        $this->createTable('sale_services_type', [
            'id' => $this->primaryKey(),
            'services_id' => $this->integer(),
            'title_uz' => $this->string(),
            'title_ru' => $this->string(),
            'order_column' => $this->smallInteger(),
            'click_count' => $this->integer(),
            'status' => $this->smallInteger()
        ]);

        $this->createTable('sale_service_statistics', [
            'id' => $this->primaryKey(),
            'chat_id' => $this->integer(),
            'type' => $this->string(),
            'button_id' => $this->integer(),
            'click_count' => $this->integer(),
        ]);

        $this->createTable('sale_step', [
            'id' => $this->primaryKey(),
            'chat_id' => $this->integer(),
            'step_1' => $this->integer(),
            'step_2' => $this->integer(),
        ]);

        $this->createTable('sale_users', [
            'id' => $this->primaryKey(),
            'chat_id' => $this->integer(),
            'username' => $this->string(),
            'phone_number' => $this->string()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210816_190329_sale_tables cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210816_190329_sale_tables cannot be reverted.\n";

        return false;
    }
    */
}
