<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "brigada".
 *
 * @property int $id
 * @property string $title_uz
 * @property string $title_ru
 * @property int|null $status
 * @property int|null $user_id
 */
class Brigada extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */

    public static function tableName()
    {
        return 'brigada';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title_uz'], 'required'],
            [['user_id'], 'default', 'value' => null],
            [['title_ru'], 'default', 'value' => 1],
            [['status', 'user_id'], 'integer'],
            [['title_uz'], 'string', 'max' => 70],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title_uz' => 'Nomi Uz',
            'status' => 'Holati',
            'user_id' => 'Brigadir',
        ];
    }

    public function getParent() 
    {
        $model = $this->hasOne(Users::class, ['id' => 'user_id']);

        return $model;
    }
}
