<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "order_categories".
 *
 * @property int $id
 * @property int|null $category_id
 * @property int|null $order_id
 * @property int|null $status
 */
class OrderCategories extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'order_categories';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['category_id', 'order_id', 'status'], 'default', 'value' => null],
            [['category_id', 'order_id', 'status'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'category_id' => 'Category ID',
            'order_id' => 'Order ID',
            'status' => 'Status',
        ];
    }

    public function getCategory()
    {
        return $this->hasOne(Category::classname(), ['id' => 'category_id']);
    }
}
