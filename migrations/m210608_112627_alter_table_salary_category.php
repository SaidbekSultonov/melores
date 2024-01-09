<?php

use yii\db\Migration;

/**
 * Class m210608_112627_alter_table_salary_category
 */
class m210608_112627_alter_table_salary_category extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('task_status', 'step_deadline', $this->integer());

        $this->addColumn('salary_category', 'type', $this->smallInteger());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210608_112627_alter_table_salary_category cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210608_112627_alter_table_salary_category cannot be reverted.\n";

        return false;
    }
    */
}
