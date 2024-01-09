<?php

use yii\db\Migration;

/**
 * Class m220117_101249_create_brigada
 */
class m220117_101249_create_brigada extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('brigada', [
            'id' => $this->primaryKey(),
            'title_uz' => $this->string(70)->notNull(),
            'title_ru' => $this->string(70)->notNull(),
            'status' => $this->integer()->defaultValue(1),
            'user_id' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('brigada');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220117_101249_create_brigada cannot be reverted.\n";

        return false;
    }
    */
}
