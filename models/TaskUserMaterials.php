<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "task_user_materials".
 *
 * @property int $id
 * @property int|null $task_id
 * @property int|null $user_id
 * @property string|null $file_id
 * @property string|null $type
 */
class TaskUserMaterials extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'task_user_materials';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['task_id', 'user_id'], 'default', 'value' => null],
            [['task_id', 'user_id'], 'integer'],
            [['file_id', 'type'], 'string', 'max' => 255],
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
            'file_id' => 'File ID',
            'type' => 'Type',
        ];
    }
}
