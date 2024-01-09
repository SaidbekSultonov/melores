<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "paused_orders".
 *
 * @property int $id
 * @property int|null $order_id
 * @property string|null $start_date
 * @property string|null $end_date
 */
class PausedOrders extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'paused_orders';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_id'], 'default', 'value' => null],
            [['order_id'], 'integer'],
            [['start_date', 'end_date'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_id' => 'Order ID',
            'start_date' => 'Start Date',
            'end_date' => 'End Date',
        ];
    }
}
