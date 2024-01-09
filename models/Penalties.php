<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "penalties".
 *
 * @property int $id
 * @property int|null $quiz_id
 * @property int|null $user_id
 * @property float|null $penalty
 */
class Penalties extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'penalties';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['quiz_id', 'user_id'], 'default', 'value' => null],
            [['quiz_id', 'user_id'], 'integer'],
            [['penalty'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'quiz_id' => 'Quiz ID',
            'user_id' => 'User ID',
            'penalty' => 'Penalty',
        ];
    }
}
