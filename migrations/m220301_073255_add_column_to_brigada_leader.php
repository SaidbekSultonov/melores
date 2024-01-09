<?php

use yii\db\Migration;

/**
 * Class m220301_073255_add_column_to_brigada_leader
 */
class m220301_073255_add_column_to_brigada_leader extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('brigada_leader', 'section_id', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m220301_073255_add_column_to_brigada_leader cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220301_073255_add_column_to_brigada_leader cannot be reverted.\n";

        return false;
    }
    */
}
