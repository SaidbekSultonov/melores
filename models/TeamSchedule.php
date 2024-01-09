<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "team_schedule".
 *
 * @property int $id
 * @property int|null $order_id
 * @property int|null $team_id
 * @property string|null $date
 */
class TeamSchedule extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'team_schedule';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_id', 'team_id', 'date'], 'required'],
            [['order_id', 'team_id'], 'integer'],
            [['date'], 'safe'],
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
            'team_id' => 'Team ID',
            'date' => 'Date',
        ];
    }
}
