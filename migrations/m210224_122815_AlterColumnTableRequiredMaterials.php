<?php

use yii\db\Migration;

/**
 * Class m210224_122815_AlterColumnTableRequiredMaterials
 */
class m210224_122815_AlterColumnTableRequiredMaterials extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = "ALTER TABLE required_material_order ALTER COLUMN file TYPE text;";
        $this->execute($sql);
        $sql = "ALTER TABLE required_material_order ALTER COLUMN title TYPE text;";
        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210224_122815_AlterColumnTableRequiredMaterials cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210224_122815_AlterColumnTableRequiredMaterials cannot be reverted.\n";

        return false;
    }
    */
}
