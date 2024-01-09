<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_balls".
 *
 * @property int $id
 * @property int|null $feedback_user_id
 * @property float|null $ball
 * @property string|null $created_date
 * @property int|null $chat_id
 * @property int|null $order_id
 */
class UserBalls extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_balls';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['feedback_user_id', 'chat_id', 'order_id'], 'default', 'value' => null],
            [['feedback_user_id', 'chat_id', 'order_id'], 'integer'],
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
            'feedback_user_id' => 'Feedback User ID',
            'ball' => 'Ball',
            'created_date' => 'Created Date',
            'chat_id' => 'Chat ID',
            'order_id' => 'Order ID',
        ];
    }
}
