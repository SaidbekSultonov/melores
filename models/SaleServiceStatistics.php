<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sale_service_statistics".
 *
 * @property int $id
 * @property int|null $chat_id
 * @property string|null $type
 * @property int|null $button_id
 * @property int|null $click_count
 */
class SaleServiceStatistics extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sale_service_statistics';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['chat_id', 'button_id', 'click_count'], 'default', 'value' => null],
            [['chat_id', 'button_id', 'click_count'], 'integer'],
            [['type'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'chat_id' => 'Chat ID',
            'type' => 'Type',
            'button_id' => 'Button ID',
            'click_count' => 'Click Count',
        ];
    }

    public function getService()
    {
        return $this->hasOne(SaleServices::classname(), ['id' => 'button_id']);
    }

    public function getSety()
    {
        return $this->hasOne(SaleServicesType::classname(), ['id' => 'button_id']);
    }

    public function getCompany()
    {
        return $this->hasOne(SaleCompany::classname(), ['id' => 'button_id']);
    }

    public function getDistrict()
    {
        return $this->hasOne(SaleDistrict::classname(), ['id' => 'button_id']);
    }

    public function getPhone()
    {
        return $this->hasOne(SaleUsers::classname(), ['chat_id' => 'chat_id']);
    }
}
