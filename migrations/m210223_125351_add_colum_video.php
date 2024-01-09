<?php

use yii\db\Migration;

/**
 * Class m210223_125351_add_colum_video
 */
class m210223_125351_add_colum_video extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('video', 'type', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210223_125351_add_colum_video cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210223_125351_add_colum_video cannot be reverted.\n";

        return false;
    }
    */
}
