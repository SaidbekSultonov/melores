<?php

use yii\db\Migration;

/**
 * Class m210228_115452_add_otdel_log
 */
class m210228_115452_add_otdel_log extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('otdel_log_message', [
            'id' => $this->primaryKey(),
            'chat_id' => $this->integer(),
            'text' => $this->string(),
            'status' => $this->integer()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210228_115452_add_otdel_log cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210228_115452_add_otdel_log cannot be reverted.\n";

        return false;
    }
    */
}
