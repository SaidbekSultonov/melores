<?php

use yii\db\Migration;

/**
 * Class m210618_110422_alter_section_minimal
 */
class m210618_110422_alter_section_minimal extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('section_minimal', 'order_id', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210618_110422_alter_section_minimal cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210618_110422_alter_section_minimal cannot be reverted.\n";

        return false;
    }
    */
}
