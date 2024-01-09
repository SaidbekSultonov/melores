<?php

use yii\db\Migration;

/**
 * Class m220728_111234_add_column_user_id_to_branchs
 */
class m220728_111234_add_column_user_id_to_branchs extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('branch', 'user_id', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m220728_111234_add_column_user_id_to_branchs cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220728_111234_add_column_user_id_to_branchs cannot be reverted.\n";

        return false;
    }
    */
}
