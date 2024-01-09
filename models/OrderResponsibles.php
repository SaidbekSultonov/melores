<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "orders".
 *
 * @property int $id
 * @property string $title
 * @property int $branch_id
 * @property int $user_id
 * @property int $client_id
 * @property string $created_date
 * @property string $dead_line
 * @property int $status
 * @property double $feedback_user
 * @property double $feedback_client
 */
class OrderResponsibles extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'order_responsibles';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_id', 'user_id', 'section_id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_id' => 'Title',
            'user_id' => 'Branch ID',
            'section_id' => 'User ID',
        ];
    }

    public function getUser()
    {
        return $this->hasOne(Users::classname(), ['id' => 'user_id']);
    }

    public function getOrder()
    {
        return $this->hasOne(Orders::classname(), ['id' => 'order_id']);
    }
    
}
