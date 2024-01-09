<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "task_user".
 *
 * @property int $id
 * @property int|null $task_id
 * @property int|null $user_id
 */
class TaskUser extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'task_user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['task_id', 'user_id'], 'default', 'value' => null],
            [['task_id', 'user_id'], 'integer'],
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
        ];
    }
}