<?php

use yii\db\Migration;

/**
 * Class m210224_041543_drop_table_salary
 */
class m210224_041543_drop_table_salary extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('salary');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210224_041543_drop_table_salary cannot be reverted.\n";

        return false;
    }
    */
}
