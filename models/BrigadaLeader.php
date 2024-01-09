<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "brigada_leader".
 *
 * @property int $id
 * @property int|null $brigada_id
 * @property int|null $user_id
 */
class BrigadaLeader extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'brigada_leader';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['brigada_id', 'user_id'], 'default', 'value' => null],
            [['brigada_id', 'user_id'], 'integer',],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'brigada_id' => 'Brigada',
            'user_id' => 'Brigadir',
        ];
    }
}
