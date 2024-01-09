<?php

use yii\db\Migration;

/**
 * Class m210318_190324_add_column_send_video
 */
class m210318_190324_add_column_send_video extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('send_video', 'chat_id', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210318_190324_add_column_send_video cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210318_190324_add_column_send_video cannot be reverted.\n";

        return false;
    }
    */
}
