<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "step".
 *
 * @property int $id
 * @property int $chat_id
 * @property int $step_1
 * @property int $step_2
 */
class Step extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'step';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['chat_id', 'step_1', 'step_2'], 'default', 'value' => null],
            [['chat_id', 'step_1', 'step_2'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'chat_id' => 'Chat ID',
            'step_1' => 'Step 1',
            'step_2' => 'Step 2',
        ];
    }
}
