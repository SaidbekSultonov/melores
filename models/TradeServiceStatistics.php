<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "trade_service_statistics".
 *
 * @property int $id
 * @property int|null $chat_id
 * @property string|null $type
 * @property int|null $button_id
 * @property int|null $click_count
 */
class TradeServiceStatistics extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'trade_service_statistics';
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
        return $this->hasOne(TradeServices::classname(), ['id' => 'button_id']);
    }

    public function getSety()
    {
        return $this->hasOne(TradeServicesTypes::classname(), ['id' => 'button_id']);
    }

    public function getCompany()
    {
        return $this->hasOne(TradeCompany::classname(), ['id' => 'button_id']);
    }

    public function getDistrict()
    {
        return $this->hasOne(TradeDistrict::classname(), ['id' => 'button_id']);
    }

    public function getPhone()
    {
        return $this->hasOne(TradeUsers::classname(), ['chat_id' => 'chat_id']);
    }
}
