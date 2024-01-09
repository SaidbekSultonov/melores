<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bot_users".
 *
 * @property int $id
 * @property int|null $user_id
 * @property int|null $bot_id
 */
class BotUsers extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bot_users';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'bot_id'], 'default', 'value' => null],
            [['user_id', 'bot_id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'bot_id' => 'Bot ID',
        ];
    }
}
