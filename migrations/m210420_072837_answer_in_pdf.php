<?php

use yii\db\Migration;

/**
 * Class m210420_072837_answer_in_pdf
 */
class m210420_072837_answer_in_pdf extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('daily_answer', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'file_name' => $this->text(),
            'date' => $this->date(),
            'status' => $this->smallInteger()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210420_072837_answer_in_pdf cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210420_072837_answer_in_pdf cannot be reverted.\n";

        return false;
    }
    */
}
