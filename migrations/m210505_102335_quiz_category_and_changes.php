<?php

use yii\db\Migration;

/**
 * Class m210505_102335_quiz_category_and_changes
 */
class m210505_102335_quiz_category_and_changes extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('quiz', 'status');

        $this->dropColumn('send_quiz', 'status');

        $this->addColumn('quiz', 'type', $this->smallInteger());

        $this->createTable('quiz_category', [
            'id' => $this->primaryKey(),
            'title' => $this->text(),
        ]);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210505_102335_quiz_category_and_changes cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210505_102335_quiz_category_and_changes cannot be reverted.\n";

        return false;
    }
    */
}
