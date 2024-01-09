<?php

use yii\db\Migration;

/**
 * Class m210511_135443_penlty_changes
 */
class m210511_135443_penlty_changes extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $this->dropTable('penalties');
        $this->dropTable('penalty_users');

        $this->createTable('penalty_users', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'sum' => $this->double(),
        ]);

        $this->createTable('penalties', [
            'id' => $this->primaryKey(),
            'quiz_id' => $this->integer(),
            'user_id' => $this->integer(),
            'penalty' => $this->double(),
        ]);

        $this->addColumn('daily_answer', 'type', $this->smallInteger());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210511_135443_penlty_changes cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210511_135443_penlty_changes cannot be reverted.\n";

        return false;
    }
    */
}
