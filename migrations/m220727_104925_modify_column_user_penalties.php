<?php

use yii\db\Migration;

/**
 * Class m220727_104925_modify_column_user_penalties
 */
class m220727_104925_modify_column_user_penalties extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('user_penalties', 'delay_day_count');
        $this->addColumn('user_penalties', 'delay_day_count', $this->double());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m220727_104925_modify_column_user_penalties cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220727_104925_modify_column_user_penalties cannot be reverted.\n";

        return false;
    }
    */
}
