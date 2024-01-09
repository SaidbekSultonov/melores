<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "section_orders_control".
 *
 * @property int $id
 * @property int|null $order_id
 * @property int|null $section_id
 * @property string|null $enter_date
 * @property string|null $exit_date
 */
class SectionOrdersControl extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'section_orders_control';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_id', 'section_id'], 'default', 'value' => null],
            [['order_id', 'section_id'], 'integer'],
            [['enter_date', 'exit_date'], 'safe'],
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
            'section_id' => 'Section ID',
            'enter_date' => 'Enter Date',
            'exit_date' => 'Exit Date',
        ];
    }

    public function getOrder()
    {
        return Orders::find()->where(['id' => $this->order_id, 'status' => 1])->one();
    }
}
