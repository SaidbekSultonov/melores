<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "client_step".
 *
 * @property int $id
 * @property int|null $chat_id
 * @property int|null $step_1
 * @property int|null $step_2
 * @property int|null $client_id
 */
class ClientStep extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'client_step';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['chat_id', 'step_1', 'step_2', 'client_id'], 'default', 'value' => null],
            [['chat_id', 'step_1', 'step_2', 'client_id'], 'integer'],
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
            'client_id' => 'Client ID',
        ];
    }
}
