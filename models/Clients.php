<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "clients".
 *
 * @property int $id
 * @property string $full_name
 * @property string $phone_number
 * @property int $chat_id
 * @property int $status
 */
class Clients extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'clients';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['chat_id', 'status'], 'default', 'value' => null],
            [['chat_id', 'status','branch_id'], 'integer'],
            [['full_name','phone_number','branch_id'], 'required'],
            [['full_name'], 'string', 'max' => 50],
            [['phone_number'], 'string'],
            
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'full_name' => 'Familiya Ismi',
            'phone_number' => 'Telefon raqami',
            'chat_id' => 'Chat ID',
            'status' => 'Status',
        ];
    }

    public function getBranch()
    {
        return $this->hasOne(Branch::classname(), ['id' => 'branch_id']);
    }
}
