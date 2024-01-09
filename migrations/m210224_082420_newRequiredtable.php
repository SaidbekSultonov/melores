<?php

use yii\db\Migration;

/**
 * Class m210224_082420_newRequiredtable
 */
class m210224_082420_newRequiredtable extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('required_material_order', [
            'id' => $this->primaryKey(),
            'file' => $this->string(),
            'type' => $this->string(),
            'order_id' => $this->integer(),
            'title' => $this->string()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210224_082420_newRequiredtable cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210224_082420_newRequiredtable cannot be reverted.\n";

        return false;
    }
    */
}
