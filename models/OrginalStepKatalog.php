<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "orginal_step_katalog".
 *
 * @property int $id
 * @property int|null $lang
 * @property string|null $title
 */
class OrginalStepKatalog extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'orginal_step_katalog';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['lang'], 'default', 'value' => null],
            [['lang'], 'integer'],
            [['title'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'lang' => 'Tili',
            'title' => 'Matn',
        ];
    }
}
