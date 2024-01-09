<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "client_recommendation".
 *
 * @property int $id
 * @property int|null $client_id
 * @property int|null $full_name
 * @property string|null $phone_number
 */
class ClientRecommendation extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'client_recommendation';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['client_id', 'full_name'], 'default', 'value' => null],
            [['client_id', 'order_id'], 'integer'],
            [['phone_number'], 'string', 'max' => 13],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'client_id' => 'Client ID',
            'full_name' => 'Full Name',
            'phone_number' => 'Phone Number',
        ];
    }
}
