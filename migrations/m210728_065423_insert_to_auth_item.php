<?php

use yii\db\Migration;

/**
 * Class m210728_065423_insert_to_auth_item
 */
class m210728_065423_insert_to_auth_item extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert('auth_item', [
            'name' => 'HR',
            'type' => 1,
            'description' => 'Permission'
        ]);

        $this->insert('auth_item', [
            'name' => 'Marketer',
            'type' => 1,
            'description' => 'Permission'
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210728_065423_insert_to_auth_item cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210728_065423_insert_to_auth_item cannot be reverted.\n";

        return false;
    }
    */
}
