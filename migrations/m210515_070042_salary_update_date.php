<?php

use yii\db\Migration;

/**
 * Class m210515_070042_salary_update_date
 */
class m210515_070042_salary_update_date extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = "ALTER TABLE salary_amount 
    ALTER COLUMN date TYPE character varying;";
        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210515_070042_salary_update_date cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210515_070042_salary_update_date cannot be reverted.\n";

        return false;
    }
    */
}
