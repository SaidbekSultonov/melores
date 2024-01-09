<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sale_company".
 *
 * @property int $id
 * @property string|null $title_uz
 * @property string|null $title_ru
 * @property int|null $order_column
 * @property int|null $click_count
 */
class SaleCompany extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sale_company';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_column', 'click_count'], 'default', 'value' => null],
            [['order_column', 'click_count'], 'integer'],
            [['title_uz', 'title_ru'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title_uz' => 'Title Uz',
            'title_ru' => 'Title Ru',
            'order_column' => 'Order Column',
            'click_count' => 'Click Count',
        ];
    }
}
