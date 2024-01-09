<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "required_material_order".
 *
 * @property int $id
 * @property string|null $file
 * @property string|null $type
 * @property int|null $order_id
 * @property string|null $title
 * @property int|null $chat_id
 * @property int|null $status
 * @property int|null $message_id
 * @property string|null $text
 */
class RequiredMaterialOrder extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'required_material_order';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['file', 'title'], 'string'],
            [['order_id', 'chat_id', 'status','required_material_id'], 'default', 'value' => null],
            [['order_id', 'chat_id', 'status'], 'integer'],
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
            'file' => 'File',
            'type' => 'Type',
            'order_id' => 'Order ID',
            'title' => 'Fayl Nomi',
            'chat_id' => 'Chat ID',
            'status' => 'Status',
            'message_id' => 'Message ID',
            'text' => 'Text',
        ];
    }

    public function getMaterial()
    {
        return $this->hasOne(RequiredMaterials::classname(), ['id' => 'required_material_id']);
    }
}
