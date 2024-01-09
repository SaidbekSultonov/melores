<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "minimal".
 *
 * @property int $id
 * @property int|null $penalty_summ
 * @property int|null $bonus_sum
 */
class Minimal extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'minimal';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['penalty_summ', 'bonus_sum'], 'default', 'value' => null],
            [['penalty_summ', 'bonus_sum'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'penalty_summ' => 'Penalty Summ',
            'bonus_sum' => 'Bonus Sum',
        ];
    }
}
