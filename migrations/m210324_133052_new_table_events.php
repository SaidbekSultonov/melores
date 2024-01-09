<?php

use yii\db\Migration;

/**
 * Class m210324_133052_new_table_events
 */
class m210324_133052_new_table_events extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('events', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'created_date' => $this->timestamp(),
            'title' => $this->string(),
            'event' => $this->text(),
            'section_title' => $this->string()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210324_133052_new_table_events cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210324_133052_new_table_events cannot be reverted.\n";

        return false;
    }
    */
}
