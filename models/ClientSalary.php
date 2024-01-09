<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "client_salary".
 *
 * @property int $id
 * @property int|null $chat_id
 * @property int|null $quantity
 */
class ClientSalary extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'client_salary';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['chat_id', 'quantity', 'order_id'], 'default', 'value' => null],
            [['chat_id', 'quantity','order_id'], 'integer'],
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
            'quantity' => 'Quantity',
        ];
    }
}
