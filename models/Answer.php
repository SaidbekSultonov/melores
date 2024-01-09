<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "answer".
 *
 * @property int $id
 * @property string|null $answer
 * @property int|null $user_id
 * @property int|null $quiz_id
 * @property string|null $date
 * @property int|null $status
 */
class Answer extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'answer';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['answer'], 'string'],
            [['user_id', 'quiz_id', 'status'], 'default', 'value' => null],
            [['user_id', 'quiz_id', 'status'], 'integer'],
            [['date'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'answer' => 'Answer',
            'user_id' => 'User ID',
            'quiz_id' => 'Quiz ID',
            'date' => 'Date',
            'status' => 'Status',
        ];
    }
}
