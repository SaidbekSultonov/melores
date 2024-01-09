<?php

use yii\db\Migration;

/**
 * Class m210719_120422_usta_bot_tabl
 */
class m210719_120422_usta_bot_tabl extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('service_message_id', [
            'id' => $this->primaryKey(),
            'chat_id' => $this->integer(),
            'message_id' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210719_120422_usta_bot_tabl cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210719_120422_usta_bot_tabl cannot be reverted.\n";

        return false;
    }
    */
}
