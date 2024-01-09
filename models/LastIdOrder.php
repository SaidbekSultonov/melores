<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "last_id_order".
 *
 * @property int $id
 * @property int|null $chat_id
 * @property int|null $last_id
 */
class LastIdOrder extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'last_id_order';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['chat_id', 'last_id'], 'default', 'value' => null],
            [['chat_id', 'last_id'], 'integer'],
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
