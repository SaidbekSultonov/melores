<?php

use yii\db\Migration;

/**
 * Class m211229_093813_add_column_last_id
 */
class m211229_093813_add_column_last_id extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('last_id', 'doc_name', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m211229_093813_add_column_last_id cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m211229_093813_add_column_last_id cannot be reverted.\n";

        return false;
    }
    */
}
