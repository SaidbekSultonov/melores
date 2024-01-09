<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "users".
 *
 * @property int $id
 * @property int $chat_id
 * @property string $username
 * @property string $name
 * @property string $second_name
 * @property string $phone_number
 * @property int $type
 * @property int $status
 * @property string $link
 */
class Users extends \yii\db\ActiveRecord
{
    const ADMIN_ID = 1270367;
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'users';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['chat_id', 'type', 'status'], 'default', 'value' => null],
            [['second_name','phone_number','type'], 'required'],
            [['chat_id', 'type', 'status','bot_id'], 'integer'],
            [['username', 'name', 'second_name', 'phone_number'], 'string', 'max' => 50],
            [['link'], 'string', 'max' => 100],
            [['phone_number'], 'number'],
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
            'username' => 'Username',
            'name' => 'Ismi',
            'second_name' => 'Ismi',
            'phone_number' => 'Telefon raqami',
            'type' => 'Ro`li',
            'status' => 'Holati',
            'link' => 'Link',
        ];
    }

    public function getBranchs()
    {
        return $this->hasMany(UsersBranch::classname(), ['user_id' => 'id']);
    }

    public function getOrders_count()
    {
        $model = UserPenalties::find()
        ->where(['user_id' => $this->id])
        ->all();


         return count($model);
    }

    public function getTotal()
    {
        $model = UserPenalties::find()
        ->where(['user_id' => $this->id])
        ->sum('sum');

        return $model;
    }

    
}
