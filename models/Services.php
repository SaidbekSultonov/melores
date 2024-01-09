<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "services".
 *
 * @property int $id
 * @property string|null $title
 * @property int|null $type
 * @property int|null $click_count
 */
class Services extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'services';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title_uz','title_ru'], 'string'],
            [['type'], 'default', 'value' => null],
            [['type', 'click_count'], 'integer'],
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
            'type' => 'Type',
            'click_count' => 'Count'
        ];
    }
}
