<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "client_last_id".
 *
 * @property int $id
 * @property int|null $chat_id
 * @property int|null $last_id
 */
class ClientLastId extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'client_last_id';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['chat_id', 'last_id'], 'default', 'value' => null],
            [['chat_id', 'last_id','last_id_2'], 'integer'],
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
