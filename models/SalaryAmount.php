<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "salary_amount".
 *
 * @property int $id
 * @property int|null $user_id
 * @property int|null $chat_id
 * @property int|null $category_id
 * @property int|null $price
 * @property string|null $comment
 * @property string|null $date
 * @property int|null $type
 * @property int|null $status
 */
class SalaryAmount extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'salary_amount';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'chat_id', 'category_id', 'price', 'type', 'status'], 'default', 'value' => null],
            [['user_id', 'chat_id', 'category_id', 'price', 'type', 'status', 'task_id'], 'integer'],
            [['date'], 'safe'],
            [['comment'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'Ishchi',
            'chat_id' => 'Chat ID',
            'category_id' => 'Kategoriya',
            'price' => 'Summa',
            'comment' => 'Izoh',
            'date' => 'Sana',
            'type' => 'Type',
            'status' => 'Status',
            'task_id' => 'Task Id',
        ];
    }

    public function getUser()
    {
        return $this->hasOne(Users::classname(), ['id' => 'user_id']);
    }

    public function getCategory()
    {
        return $this->hasOne(SalaryCategory::classname(), ['id' => 'category_id']);
    }
}
