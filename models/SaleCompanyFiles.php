<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sale_company_files".
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
 * @property float|null $long
 * @property float|null $lat
 */
class SaleCompanyFiles extends \yii\db\ActiveRecord
{
    public $imageFile;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sale_company_files';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['company_id', 'status', 'services_type_id', 'district_id','long','lat'], 'default', 'value' => null],
            [['company_id', 'status', 'services_type_id', 'district_id'], 'integer'],
            [['company_id',  'services_type_id', 'district_id'], 'required'],
            [['file_id', 'type'], 'string'],
            [['long', 'lat'], 'number'],
            [['title_uz', 'title_ru'], 'string', 'max' => 255],
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
            'file_id' => 'File ID',
            'type' => 'Type',
            'status' => 'Status',
            'title_uz' => 'Title Uz',
            'title_ru' => 'Title Ru',
            'services_type_id' => 'Services Type ID',
            'district_id' => 'District ID',
            'long' => 'Long',
            'lat' => 'Lat',
        ];
    }

    public function getServices_type()
    {
        return $this->hasOne(SaleServicesType::classname(), ['id' => 'services_type_id']);
    }

    public function getDistrict()
    {
        return $this->hasOne(SaleDistrict::classname(), ['id' => 'district_id']);
    }
}
