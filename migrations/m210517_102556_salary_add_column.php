<?php

use yii\db\Migration;

/**
 * Class m210517_102556_salary_add_column
 */
class m210517_102556_salary_add_column extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('salary_amount', 'user_id', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210517_102556_salary_add_column cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210517_102556_salary_add_column cannot be reverted.\n";

        return false;
    }
    */
}
