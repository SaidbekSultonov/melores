<?php

use yii\db\Migration;

/**
 * Class m210426_094812_task_user_materials_table
 */
class m210426_094812_task_user_materials_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('task_user_materials', [
            'id' => $this->primaryKey(),
            'task_id' => $this->integer(),
            'user_id' => $this->integer(),
            'file_id' => $this->string(),
            'type' => $this->string()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210426_094812_task_user_materials_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210426_094812_task_user_materials_table cannot be reverted.\n";

        return false;
    }
    */
}
