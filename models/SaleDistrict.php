<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sale_district".
 *
 * @property int $id
 * @property int|null $status
 * @property string|null $title_uz
 * @property string|null $title_ru
 * @property int|null $click_count
 */
class SaleDistrict extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sale_district';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status', 'click_count'], 'default', 'value' => null],
            [['status', 'click_count'], 'integer'],
            [['title_uz', 'title_ru'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'status' => 'Status',
            'title_uz' => 'Title Uz',
            'title_ru' => 'Title Ru',
            'click_count' => 'Click Count',
        ];
    }
}
