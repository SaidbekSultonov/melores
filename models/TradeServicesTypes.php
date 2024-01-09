<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "trade_services_types".
 *
 * @property int $id
 * @property int|null $services_id
 * @property string|null $title_uz
 * @property string|null $title_ru
 * @property int|null $order_column
 * @property int|null $click_count
 */
class TradeServicesTypes extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'trade_services_types';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['services_id', 'order_column'], 'default', 'value' => null],
            [['services_id', 'order_column', 'click_count'], 'integer'],
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
            'services_id' => 'Services ID',
            'title_uz' => 'Title Uz',
            'title_ru' => 'Title Ru',
            'order_column' => 'Order Column',
            'click_count' => 'Count'
        ];
    }
    public function getServices()
    {
        return $this->hasOne(TradeServices::classname(), ['id' => 'services_id']);
    }
}
