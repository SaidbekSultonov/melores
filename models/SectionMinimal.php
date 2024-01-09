<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "section_minimal".
 *
 * @property int $id
 * @property int|null $section_id
 * @property int|null $minimal_id
 * @property int|null $user_id
 * @property int|null $order_id
 */
class SectionMinimal extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'section_minimal';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['section_id', 'minimal_id', 'user_id', 'order_id'], 'default', 'value' => null],
            [['section_id', 'minimal_id', 'user_id', 'order_id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'section_id' => 'Section ID',
            'minimal_id' => 'Minimal ID',
            'user_id' => 'User ID',
            'order_id' => 'Order ID',
        ];
    }

    public function getMinimal()
    {
        return $this->hasOne(Minimal::classname(), ['id' => 'minimal_id']);
    }

    public function getUser()
    {
        return $this->hasOne(Users::classname(), ['id' => 'user_id']);
    }

    public function getSection()
    {
        return $this->hasOne(Sections::classname(), ['id' => 'section_id']);
    }
}
