<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "task_status".
 *
 * @property int $id
 * @property int|null $task_id
 * @property int|null $user_id
 * @property int|null $status
 * @property string|null $enter_date
 * @property string|null $end_date
 * @property int|null $step_cron
 * @property string|null $task_end_date
 */
class TaskStatus extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'task_status';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['task_id', 'user_id', 'status', 'step_cron'], 'default', 'value' => null],
            [['task_id', 'user_id', 'status', 'step_cron'], 'integer'],
            [['enter_date', 'end_date', 'task_end_date'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'task_id' => 'Task ID',
            'user_id' => 'User ID',
            'status' => 'Status',
            'enter_date' => 'Enter Date',
            'end_date' => 'End Date',
            'step_cron' => 'Step Cron',
            'task_end_date' => 'Task End Date',
        ];
    }
}
