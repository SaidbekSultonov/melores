<?php

use yii\db\Migration;

/**
 * Class m210505_114041_category_id_add_to_quiz
 */
class m210505_114041_category_id_add_to_quiz extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('quiz', 'category_id', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210505_114041_category_id_add_to_quiz cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210505_114041_category_id_add_to_quiz cannot be reverted.\n";

        return false;
    }
    */
}
