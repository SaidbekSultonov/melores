<?php

use yii\db\Migration;

/**
 * Class m210305_064827_add_permission_auth_item
 */
class m210305_064827_add_permission_auth_item extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = "INSERT INTO auth_item (name, type, description) VALUES('Admin', 1, 'Permission');";
        $this->execute($sql);

        $sql = "INSERT INTO auth_item (name, type, description) VALUES('Manager', 1, 'Permission');";
        $this->execute($sql);

        $sql = "INSERT INTO auth_item (name, type, description) VALUES('Boss', 1, 'Permission');";
        $this->execute($sql);

        $sql = "INSERT INTO auth_item (name, type, description) VALUES('Kroy', 1, 'Permission');";
        $this->execute($sql);

        $sql = "INSERT INTO auth_item (name, type, description) VALUES('OTK', 1, 'Permission');";
        $this->execute($sql);

        $sql = "INSERT INTO auth_item (name, type, description) VALUES('Sales', 1, 'Permission');";
        $this->execute($sql);

        $sql = "INSERT INTO auth_item (name, type, description) VALUES('Worker', 1, 'Permission');";
        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210305_064827_add_permission_auth_item cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210305_064827_add_permission_auth_item cannot be reverted.\n";

        return false;
    }
    */
}
