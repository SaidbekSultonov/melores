<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "check_video".
 *
 * @property int $id
 * @property int|null $chat_id
 * @property int|null $video_number
 * @property string|null $send_date
 * @property string|null $next_send_date
 * @property int|null $send
 * @property int|null $status
 */
class CheckVideo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'check_video';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['chat_id', 'video_number', 'send', 'status'], 'default', 'value' => null],
            [['chat_id', 'video_number', 'send', 'status'], 'integer'],
            [['send_date', 'next_send_date'], 'safe'],
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
            'video_number' => 'Video Number',
            'send_date' => 'Send Date',
            'next_send_date' => 'Next Send Date',
            'send' => 'Send',
            'status' => 'Status',
        ];
    }
}
