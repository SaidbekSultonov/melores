<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "task_materials".
 *
 * @property int $id
 * @property int|null $task_id
 * @property string|null $file_id
 * @property string|null $caption
 * @property string|null $type
 */
class TaskMaterials extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'task_materials';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['task_id'], 'default', 'value' => null],
            [['task_id'], 'integer'],
            [['file_id', 'caption'], 'string'],
            [['type'], 'string', 'max' => 255],
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
            'file_id' => 'File ID',
            'caption' => 'Caption',
            'type' => 'Type',
        ];
    }
}
