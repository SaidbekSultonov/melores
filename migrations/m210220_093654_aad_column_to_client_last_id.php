<?php

use yii\db\Migration;

/**
 * Class m210220_093654_aad_column_to_client_last_id
 */
class m210220_093654_aad_column_to_client_last_id extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = "ALTER TABLE client_last_id ADD COLUMN last_id_2 integer;";
        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210220_093654_aad_column_to_client_last_id cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210220_093654_aad_column_to_client_last_id cannot be reverted.\n";

        return false;
    }
    */
}
