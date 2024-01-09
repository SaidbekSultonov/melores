<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "order_graph".
 *
 * @property int $id
 * @property int|null $order_id
 * @property int|null $section_id
 * @property string|null $deadline
 */
class OrderGraph extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'order_graph';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_id', 'section_id'], 'default', 'value' => null],
            [['order_id', 'section_id'], 'integer'],
            [['deadline'], 'safe'],
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
            'deadline' => 'Deadline',
        ];
    }
}
