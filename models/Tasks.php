<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tasks".
 *
 * @property int $id
 * @property int|null $admin_id
 * @property int|null $task_fine
 * @property int|null $status
 * @property string|null $created_date
 * @property string|null $dead_line
 */
class Tasks extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tasks';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['admin_id', 'task_fine', 'deadline_fine', 'status'], 'default', 'value' => null],
            [['admin_id', 'task_fine', 'deadline_fine', 'status'], 'integer'],
            [['created_date'], 'safe'],
            [['dead_line'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'admin_id' => 'Admin',
            'task_fine' => 'Vaqtida bitmaganlik uchun jarima',
            'deadline_fine' => 'Vaqtida qabul qilmaganligi uchun jarima',
            'status' => 'Holati',
            'created_date' => 'Vazifa berilgan sana',
            'dead_line' => 'Vazifa tugatilish sanasi',
        ];
    }

    public function getFile()
    {
        return $this->hasOne(TaskMaterials::classname(), ['task_id' => 'id']);
    }

    public function getUsers()
    {
        return $this->hasMany(TaskUser::classname(), ['task_id' => 'id']);
    }

    public function getActive()
    {
        return $this->hasOne(TaskStatus::classname(), ['task_id' => 'id']);
    }
}
