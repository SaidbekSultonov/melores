<?php

use yii\db\Migration;

/**
 * Class m210618_072100_bonuses
 */
class m210618_072100_bonuses extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('minimal', [
            'id' => $this->primaryKey(),
            'penalty_summ' => $this->integer(),
            'bonus_sum' => $this->integer()
        ]);
        $this->createTable('section_minimal', [
            'id' => $this->primaryKey(),
            'section_id' => $this->integer(),
            'minimal_id' => $this->integer(),
            'user_id' => $this->integer()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210618_072100_bonuses cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210618_072100_bonuses cannot be reverted.\n";

        return false;
    }
    */
}
