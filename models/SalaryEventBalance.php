<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "salary_event_balance".
 *
 * @property int $id
 * @property int|null $user_id
 * @property int|null $receiver
 * @property int|null $quantity
 * @property int|null $category_id
 * @property string|null $date
 * @property int|null $type
 */
class SalaryEventBalance extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'salary_event_balance';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'receiver', 'quantity', 'category_id', 'type'], 'default', 'value' => null],
            [['user_id', 'receiver', 'quantity', 'category_id', 'type'], 'integer'],
            [['date'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'receiver' => 'Receiver',
            'quantity' => 'Quantity',
            'category_id' => 'Category ID',
            'date' => 'Date',
            'type' => 'Type',
        ];
    }
}
