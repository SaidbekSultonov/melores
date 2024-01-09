<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "users_branch".
 *
 * @property int $id
 * @property int|null $user_id
 * @property int|null $branch_id
 */
class UsersBranch extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'users_branch';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'branch_id'], 'default', 'value' => null],
            [['user_id', 'branch_id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'branch_id' => 'Branch ID',
        ];
    }

    public function getBranch()
    {
        return $this->hasOne(Branch::classname(), ['id' => 'branch_id']);
    }
}
