<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sale_company_services_type".
 *
 * @property int $id
 * @property int|null $company_id
 * @property int|null $services_type_id
 */
class SaleCompanyServicesType extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sale_company_services_type';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['company_id', 'services_type_id'], 'default', 'value' => null],
            [['company_id', 'services_type_id'], 'integer'],
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
            'services_type_id' => 'Services Type ID',
        ];
    }
}
