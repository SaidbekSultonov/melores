<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "section_orders".
 *
 * @property int $id
 * @property int $order_id
 * @property int $section_id
 * @property string $enter_date
 * @property string $exit_date
 */
class SectionOrders extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'section_orders';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_id', 'section_id'], 'default', 'value' => null],
            [['order_id', 'section_id','step'], 'integer'],
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

    public function getSection()
    {
        return Sections::find()->where(['id' => $this->section_id, 'status' => 1])->one();
    }
}
