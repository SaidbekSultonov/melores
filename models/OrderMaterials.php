<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "order_materials".
 *
 * @property int $id
 * @property string $title
 * @property string $file
 * @property string $type
 * @property int $order_id
 * @property int $user_id
 * @property string $created_date
 * @property int $status
 */
class OrderMaterials extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'order_materials';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_id', 'user_id', 'status'], 'default', 'value' => null],
            [['order_id', 'user_id', 'status','chat_id','copy_message_id'], 'integer'],
            [['created_date'], 'safe'],
            [['title', 'file'], 'string', 'max' => 255],
            [['type'], 'string', 'max' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'file' => 'File',
            'type' => 'Type',
            'order_id' => 'Order ID',
            'user_id' => 'User ID',
            'created_date' => 'Created Date',
            'status' => 'Status',
        ];
    }

    public function getUser()
    {
        return $this->hasOne(Users::classname(), ['id' => 'user_id']);
    }
}
