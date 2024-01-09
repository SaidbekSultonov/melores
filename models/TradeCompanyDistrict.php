<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "trade_company_district".
 *
 * @property int $id
 * @property int|null $company_id
 * @property int|null $district_id
 */
class TradeCompanyDistrict extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'trade_company_district';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['company_id', 'district_id'], 'default', 'value' => null],
            [['company_id', 'district_id'], 'integer'],
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
            'district_id' => 'District ID',
        ];
    }
}
