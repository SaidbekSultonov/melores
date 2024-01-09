<?php

use yii\db\Migration;

/**
 * Class m210423_114420_task_materials_change_type
 */
class m210423_114420_task_materials_change_type extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = "ALTER TABLE task_materials 
    ALTER COLUMN file_id TYPE TEXT,
    ALTER COLUMN caption TYPE TEXT;";
        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210423_114420_task_materials_change_type cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210423_114420_task_materials_change_type cannot be reverted.\n";

        return false;
    }
    */
}
