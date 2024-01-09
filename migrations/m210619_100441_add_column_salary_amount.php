<?php

use yii\db\Migration;

/**
 * Class m210619_100441_add_column_salary_amount
 */
class m210619_100441_add_column_salary_amount extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('salary_amount', 'task_id', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210619_100441_add_column_salary_amount cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210619_100441_add_column_salary_amount cannot be reverted.\n";

        return false;
    }
    */
}
