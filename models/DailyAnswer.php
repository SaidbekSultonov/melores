<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "daily_answer".
 *
 * @property int $id
 * @property int|null $user_id
 * @property string|null $file_name
 * @property string|null $date
 * @property int|null $status
 * @property int|null $type
 */
class DailyAnswer extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'daily_answer';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'status', 'type'], 'default', 'value' => null],
            [['user_id', 'status', 'type'], 'integer'],
            [['file_name'], 'string'],
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
            'user_id' => 'User ID',
            'file_name' => 'File Name',
            'date' => 'Date',
            'status' => 'Status',
            'type' => 'Type',
        ];
    }
}
