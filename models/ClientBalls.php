<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "client_balls".
 *
 * @property int $id
 * @property int|null $feedback_client_id
 * @property float|null $ball
 * @property string|null $created_date
 * @property int|null $chat_id
 * @property int|null $order_id
 */
class ClientBalls extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'client_balls';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['feedback_client_id', 'chat_id', 'order_id'], 'default', 'value' => null],
            [['feedback_client_id', 'chat_id', 'order_id'], 'integer'],
            [['ball'], 'number'],
            [['created_date'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'feedback_client_id' => 'Feedback Client ID',
            'ball' => 'Ball',
            'created_date' => 'Created Date',
            'chat_id' => 'Chat ID',
            'order_id' => 'Order ID',
        ];
    }
}
