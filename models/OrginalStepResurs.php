<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "orginal_step_resurs".
 *
 * @property int $id
 * @property string|null $file_id
 * @property string|null $type
 * @property int|null $lang
 * @property int|null $category
 * @property string|null $caption
 */
class OrginalStepResurs extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'orginal_step_resurs';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['file_id', 'type', 'caption'], 'string'],
            [['lang', 'category'], 'default', 'value' => null],
            [['lang', 'category'], 'integer'],
            [['file_id'], 'file', 'extensions' => 'png, jpg, mp4, jpeg, mp3'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'file_id' => 'Media',
            'type' => 'Turi',
            'lang' => 'Tili',
            'category' => 'Kategoriya',
            'caption' => 'Tavsif',
        ];
    }
}
