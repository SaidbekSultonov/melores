<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "team".
 *
 * @property int $id
 * @property string $title
 * @property int $branch_id
 * @property int $user_id
 * @property int $status
 */
class Team extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'team';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['branch_id', 'status'], 'default', 'value' => null],
            [['branch_id', 'status'], 'integer'],
            [['title'], 'required'],
            [['title'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Nomi',
            'branch_id' => 'Filial',
            'status' => 'Holati',
        ];
    }

    public function getBranch()
    {
        return $this->hasOne(Branch::classname(), ['id' => 'branch_id']);
    }
}
