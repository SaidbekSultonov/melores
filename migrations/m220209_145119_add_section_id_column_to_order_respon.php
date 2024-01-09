<?php

use yii\db\Migration;

/**
 * Class m220209_145119_add_section_id_column_to_order_respon
 */
class m220209_145119_add_section_id_column_to_order_respon extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = "ALTER TABLE order_responsibles ADD COLUMN section_id bigint";
        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m220209_145119_add_section_id_column_to_order_respon cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220209_145119_add_section_id_column_to_order_respon cannot be reverted.\n";

        return false;
    }
    */
}
