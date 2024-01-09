<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sale_services_type".
 *
 * @property int $id
 * @property int|null $services_id
 * @property string|null $title_uz
 * @property string|null $title_ru
 * @property int|null $order_column
 * @property int|null $click_count
 * @property int|null $status
 */
class SaleServicesType extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sale_services_type';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['services_id', 'order_column', 'click_count', 'status'], 'default', 'value' => null],
            [['services_id', 'order_column', 'click_count', 'status'], 'integer'],
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
            'click_count' => 'Click Count',
            'status' => 'Status',
        ];
    }

    public function getServices()
    {
        return $this->hasOne(SaleServices::classname(), ['id' => 'services_id']);
    }
}
