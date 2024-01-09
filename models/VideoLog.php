<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "video_log".
 *
 * @property int $id
 * @property int|null $chat_id
 * @property string|null $file_id
 * @property int|null $video_id
 * @property string|null $type
 */
class VideoLog extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'video_log';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['chat_id', 'video_id'], 'default', 'value' => null],
            [['chat_id', 'video_id'], 'integer'],
            [['file_id'], 'string', 'max' => 300],
            [['type'], 'string', 'max' => 50],
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
            'file_id' => 'File ID',
            'video_id' => 'Video ID',
            'type' => 'Type',
        ];
    }
}
