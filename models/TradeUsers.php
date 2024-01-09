<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "trade_users".
 *
 * @property int $id
 * @property int|null $chat_id
 * @property string|null $username
 * @property string|null $phone_number
 */
class TradeUsers extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'trade_users';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['chat_id'], 'default', 'value' => null],
            [['chat_id'], 'integer'],
            [['username', 'phone_number'], 'string', 'max' => 255],
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
            'username' => 'Username',
            'phone_number' => 'Phone Number',
        ];
    }
}
