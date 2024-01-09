<?php

use yii\db\Migration;

/**
 * Class m210507_114001_add_column_quiz_id
 */
class m210507_114001_add_column_quiz_id extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('penalty_users', 'quiz_id', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210507_114001_add_column_quiz_id cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210507_114001_add_column_quiz_id cannot be reverted.\n";

        return false;
    }
    */
}
