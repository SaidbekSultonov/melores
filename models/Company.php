<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "company".
 *
 * @property int $id
 * @property string|null $title
 * @property int|null $district_id
 * @property int|null $services_type_id
 * @property int|null $click_count
 */
class Company extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'company';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title_uz','title_ru'], 'string'],
            [['click_count'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'district_id' => 'District ID',
            'services_type_id' => 'Services Type ID',
            'click_count' => 'Count'
        ];
    }
}
