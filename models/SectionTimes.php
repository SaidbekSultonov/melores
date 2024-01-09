<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "section_times".
 *
 * @property int $id
 * @property int|null $section_id
 * @property int|null $work_time
 * @property string|null $start_date
 * @property string|null $end_date
 * @property int|null $status
 */
class SectionTimes extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'section_times';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['section_id', 'work_time', 'status'], 'default', 'value' => null],
            [['section_id', 'work_time', 'status'], 'integer'],
            [['start_date', 'end_date'], 'safe'],
            [['work_time'], 'required'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'section_id' => 'Section ID',
            'work_time' => 'Ish soati',
            'start_date' => 'Start Date',
            'end_date' => 'End Date',
            'status' => 'Status',
        ];
    }
}
