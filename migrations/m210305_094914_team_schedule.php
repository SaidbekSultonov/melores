<?php

use yii\db\Migration;

/**
 * Class m210305_094914_team_schedule
 */
class m210305_094914_team_schedule extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('team_schedule', [
            'id' => $this->primaryKey(),
            'order_id' => $this->integer(),
            'team_id' => $this->integer(),
            'date' => $this->TIMESTAMP()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210305_094914_team_schedule cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210305_094914_team_schedule cannot be reverted.\n";

        return false;
    }
    */
}
