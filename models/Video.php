<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "video".
 *
 * @property int $id
 * @property string|null $file_id
 * @property int|null $video_number
 * @property int|null $status
 */
class Video extends \yii\db\ActiveRecord
{
    public $imageFile;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'video';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status', 'caption'], 'default', 'value' => null],
            [['status'], 'integer'],
            [['type'], 'string', 'max' => 255],
            [['file_id'], 'string'],
            [['imageFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg, mp4, avi', 'maxSize' => 1024*1024*10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'file_id' => 'File',
            'type' => 'Turi',
            'caption' => 'Caption',
            'status' => 'Holati',
        ];
    }
}
