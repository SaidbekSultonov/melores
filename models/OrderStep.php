<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "order_step".
 *
 * @property int $id
 * @property int $order_id
 * @property int $section_id
 * @property string $deadline
 */
class OrderStep extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'order_step';
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

    public function getSection_orders()
    {
        $model = SectionOrders::find()
        ->where([
            'order_id' => $this->order_id, 
            'section_id' => $this->section_id
        ])
        ->one();

        if (isset($model) && $model->exit_date == NULL) {
            return 'timeline-step--current';
        }
        else{
            return '';
        }
    }

    public function getSection()
    {
        return $this->hasOne(Sections::classname(), ['id' => 'section_id']);
    }
    public function getOrder()
    {
        return $this->hasOne(Orders::classname(), ['id' => 'order_id']);
    }

    public function getSection_orders_enter_date()
    {
        $model = SectionOrders::find()
        ->where([
            'order_id' => $this->order_id, 
            'section_id' => $this->section_id
        ])
        ->one();

        if (isset($model)) {
            return date("d-m-Y H:i",strtotime(date($model->enter_date)));    
        }
        else{
            return '';    
        }

        
        
        
    }

    public function getSection_orders_exit_date()
    {
        $model = SectionOrders::find()
        ->where([
            'order_id' => $this->order_id, 
            'section_id' => $this->section_id
        ])
        ->one();

        
        if (isset($model)) {
            return (($model->exit_date) ? date("d-m-Y H:i",strtotime(date($model->exit_date))) : '');    
        }
        else{
            return '';    
        }
        
    }

    public function getSection_minimal()
    {
        return $this->hasOne(SectionMinimal::classname(), ['section_id' => 'section_id']);
    }
}
