<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "brigada_users".
 *
 * @property int $id
 * @property int|null $brigada_id
 * @property int|null $user_id
 */
class BrigadaUsers extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'brigada_users';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['brigada_id', 'user_id'], 'default', 'value' => null],
            [['brigada_id', 'user_id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'brigada_id' => 'Brigada ID',
            'user_id' => 'Ishchilar',
        ];
    }

    public function getParent() 
    {
        $model = $this->hasOne(Users::class, ['id' => 'user_id']);

        return $model;
    }
}
