<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bot".
 *
 * @property int $id
 * @property string|null $username
 * @property string|null $token
 * @property string|null $link
 */
class Bot extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bot';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'token', 'link'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'token' => 'Token',
            'link' => 'Link',
        ];
    }
}
