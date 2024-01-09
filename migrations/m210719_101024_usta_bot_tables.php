<?php

use yii\db\Migration;

/**
 * Class m210719_101024_usta_bot_tables
 */
class m210719_101024_usta_bot_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('service_users', [
            'id' => $this->primaryKey(),
            'chat_id' => $this->integer(),
            'username' => $this->string(),
            'phone_number' => $this->string(),
        ]);

        $this->createTable('service_step', [
            'id' => $this->primaryKey(),
            'chat_id' => $this->integer(),
            'step_1' => $this->smallInteger(),
            'step_2' => $this->smallInteger(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210719_101024_usta_bot_tables cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210719_101024_usta_bot_tables cannot be reverted.\n";

        return false;
    }
    */
}
