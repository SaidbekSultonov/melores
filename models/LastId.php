<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "last_id".
 *
 * @property int $id
 * @property int $chat_id
 * @property int $last_id
 */
class LastId extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'last_id';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['chat_id', 'last_id', 'video_last_id'], 'default', 'value' => null],
            [['chat_id', 'last_id', 'video_last_id'], 'integer'],
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
            'last_id' => 'Last ID',
        ];
    }
}
