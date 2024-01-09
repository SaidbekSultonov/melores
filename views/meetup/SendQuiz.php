<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "send_quiz".
 *
 * @property int $id
 * @property int|null $user_id
 * @property int|null $quiz_id
 */
class SendQuiz extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'send_quiz';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'quiz_id'], 'default', 'value' => null],
            [['user_id', 'quiz_id'], 'integer'],
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
            'quiz_id' => 'Quiz ID',
        ];
    }
}
