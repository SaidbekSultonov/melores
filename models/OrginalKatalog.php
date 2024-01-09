<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "orginal_katalog".
 *
 * @property int $id
 * @property string|null $file_id
 * @property string|null $caption
 * @property int|null $lang
 * @property int|null $category
 * @property string|null $type
 */
class OrginalKatalog extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'orginal_katalog';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['file_id', 'caption'], 'string'],
            [['lang', 'category'], 'default', 'value' => null],
            [['lang', 'category'], 'integer'],
            [['type'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'file_id' => 'File ID',
            'caption' => 'Tavsif',
            'lang' => 'Tili',
            'category' => 'Kategoriya',
            'type' => 'Turi',
        ];
    }

    public function getCat()
    {
        return $this->hasOne(OrginalStepKatalog::classname(), ['id' => 'category']);
    }
}
