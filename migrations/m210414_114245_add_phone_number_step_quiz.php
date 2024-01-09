<?php

use yii\db\Migration;

/**
 * Class m210414_114245_add_phone_number_step_quiz
 */
class m210414_114245_add_phone_number_step_quiz extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = "ALTER TABLE quiz_step ADD COLUMN phone_number CHARACTER VARYING(50)";
        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210414_114245_add_phone_number_step_quiz cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210414_114245_add_phone_number_step_quiz cannot be reverted.\n";

        return false;
    }
    */
}
