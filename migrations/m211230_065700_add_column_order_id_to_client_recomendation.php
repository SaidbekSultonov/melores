<?php

use yii\db\Migration;

/**
 * Class m211230_065700_add_column_order_id_to_client_recomendation
 */
class m211230_065700_add_column_order_id_to_client_recomendation extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = "ALTER TABLE client_recommendation ADD COLUMN order_id integer;";
        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m211230_065700_add_column_order_id_to_client_recomendation cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m211230_065700_add_column_order_id_to_client_recomendation cannot be reverted.\n";

        return false;
    }
    */
}
