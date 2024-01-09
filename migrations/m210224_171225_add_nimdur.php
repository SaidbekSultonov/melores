<?php

use yii\db\Migration;

/**
 * Class m210224_171225_add_nimdur
 */
class m210224_171225_add_nimdur extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = "ALTER TABLE required_material_order ADD COLUMN chat_id bigint;";
        $this->execute($sql);
        $sql = "ALTER TABLE required_material_order ADD COLUMN status integer;";
        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210224_171225_add_nimdur cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210224_171225_add_nimdur cannot be reverted.\n";

        return false;
    }
    */
}
