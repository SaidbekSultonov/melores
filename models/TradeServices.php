<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "trade_services".
 *
 * @property int $id
 * @property int|null $type
 * @property string|null $title_uz
 * @property string|null $title_ru
 * @property int|null $order_column
 * @property int|null $click_count
 */
class TradeServices extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'trade_services';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type', 'order_column'], 'default', 'value' => null],
            [['type', 'order_column', 'click_count'], 'integer'],
            [['title_uz', 'title_ru'], 'string', 'max' => 255],
            [['category_id'], 'integer']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
            'title_uz' => 'Title Uz',
            'title_ru' => 'Title Ru',
            'order_column' => 'Order Column',
            'click_count' => 'Count',
            'category_id' => 'Category'
        ];
    }
    public function getParent() 
    {
        $model = $this->hasOne(TradeCategory::class, ['id' => 'category_id']);
        // if (isset($model->title_uz) && !empty($model->title_uz)) {
        //     return $model->title_uz;
        // } else {
        //     return "error";
        // }
        return $model;
    }

}
