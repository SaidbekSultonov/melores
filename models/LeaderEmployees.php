<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "leader_employees".
 *
 * @property int $id
 * @property int|null $leader_id
 * @property int|null $employee_id
 */
class LeaderEmployees extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'leader_employees';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['leader_id', 'employee_id'], 'default', 'value' => null],
            [['leader_id', 'employee_id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'leader_id' => 'Ma`sul shaxs',
            'employee_id' => 'Ishchi',
        ];
    }
}
