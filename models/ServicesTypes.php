<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "services_types".
 *
 * @property int $id
 * @property string|null $title
 * @property int|null $services_id
 * @property int|null $click_count
 */
class ServicesTypes extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'services_types';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title_uz','title_ru'], 'string'],
            [['services_id'], 'default', 'value' => null],
            [['services_id', 'click_count'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title_uz' => 'Title_uz',
            'title_ru' => 'Title_ru',
            'services_id' => 'Services ID',
            'click_count' => 'Count'
        ];
    }

    public function getServices()
    {
        return $this->hasOne(Services::classname(), ['id' => 'services_id']);
    }
}
