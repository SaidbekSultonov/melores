<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "feedback_user".
 *
 * @property int $id
 * @property string|null $title
 * @property int|null $status
 * @property int|null $type
 */
class FeedbackUser extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'feedback_user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status', 'type'], 'default', 'value' => null],
            [['status', 'type'], 'integer'],
            [['title'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Savol',
            'status' => 'Status',
            'type' => 'Savol turi',
        ];
    }

    public static function user_ball($order_id,$feedback_user_id)
    {
        $model = UserBalls::find()->where([
            'order_id' => $order_id,
            'feedback_user_id' => $feedback_user_id
        ])
        ->one();

        if (isset($model)) {
            return $model->ball;
        }
        else{
            return '';
        }
    }
}
