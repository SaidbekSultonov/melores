<?php

use yii\db\Migration;

/**
 * Class m210224_042038_delete_salary
 */
class m210224_042038_delete_salary extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropTable('salary');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210224_042038_delete_salary cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210224_042038_delete_salary cannot be reverted.\n";

        return false;
    }
    */
}
