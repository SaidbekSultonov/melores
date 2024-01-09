<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "company_files".
 *
 * @property int $id
 * @property int|null $company_id
 * @property string|null $file_id
 * @property string|null $type
 * @property int|null $status
 * @property string|null $title_uz
 * @property string|null $title_ru
 * @property int|null $services_type_id
 * @property int|null $district_id
 */
class CompanyFiles extends \yii\db\ActiveRecord
{
    public $imageFile;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'company_files';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['company_id', 'status', 'services_type_id', 'district_id','long','lat'], 'default', 'value' => null],
            [['company_id',  'services_type_id', 'district_id'], 'required'],
            [['company_id', 'status', 'services_type_id', 'district_id'], 'integer'],
            [['file_id'], 'string'],
            [['type', 'title_uz', 'title_ru'], 'string', 'max' => 255],
            [['imageFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg, mp4, avi'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'company_id' => 'Company ID',
            'file_id' => 'Fayl',
            'type' => 'Type',
            'status' => 'Status',
            'title_uz' => 'Nomi Uz',
            'title_ru' => 'Nomi Ru',
            'services_type_id' => 'Bo`lim',
            'district_id' => 'Hudud',
        ];
    }

    public function getServices_type()
    {
        return $this->hasOne(ServicesTypes::classname(), ['id' => 'services_type_id']);
    }

    public function getDistrict()
    {
        return $this->hasOne(District::classname(), ['id' => 'district_id']);
    }
}
