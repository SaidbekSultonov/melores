<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "events".
 *
 * @property int $id
 * @property int|null $user_id
 * @property string|null $created_date
 * @property string|null $title
 * @property string|null $event
 * @property string|null $section_title
 */
class Events extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'events';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id'], 'default', 'value' => null],
            [['user_id'], 'integer'],
            [['created_date'], 'safe'],
            [['event'], 'string'],
            [['title', 'section_title'], 'string', 'max' => 255],
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
            'created_date' => 'O`zgarish kritilgan sana',
            'title' => 'Nomi',
            'event' => 'Qanday o`zgarish qilindi',
            'section_title' => 'Bo`lim',
        ];
    }

    public function getUser()
    {
        return $this->hasOne(Users::classname(), ['id' => 'user_id']);
    }
}
