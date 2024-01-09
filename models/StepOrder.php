<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "step_order".
 *
 * @property int $id
 * @property int|null $chat_id
 * @property int|null $step_1
 * @property int|null $step_2
 */
class StepOrder extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'step_order';
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
