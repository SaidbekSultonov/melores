<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "trade_category".
 *
 * @property int $id
 * @property string|null $title_uz
 * @property string|null $title_ru
 * @property int|null $status
 */
class TradeCategory extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    const ACTIVE = 1;
    const PASSIVE = 0;
    public static function tableName()
    {
        return 'trade_category';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title_uz', 'title_ru'], 'required'],
            [['status'], 'default', 'value' => 1],
            [['status'], 'integer'],
            [['title_uz', 'title_ru'], 'string', 'max' => 150],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title_uz' => 'Nomi UZ',
            'title_ru' => 'Nomi RU',
            'status' => 'Status',
        ];
    }
}
