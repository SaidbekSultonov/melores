<?php

use yii\db\Migration;

/**
 * Class m210215_125517_add_column_team_id_to_orders
 */
class m210215_125517_add_column_team_id_to_orders extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = "ALTER TABLE orders ADD COLUMN team_id integer;";
        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210215_125517_add_column_team_id_to_orders cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210215_125517_add_column_team_id_to_orders cannot be reverted.\n";

        return false;
    }
    */
}
