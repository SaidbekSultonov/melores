<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "users_section".
 *
 * @property int $id
 * @property int|null $user_id
 * @property int|null $section_id
 */
class UsersSection extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'users_section';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'section_id'], 'default', 'value' => null],
            [['user_id', 'section_id','bot_users_id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'section_id' => 'Section ID',
        ];
    }

    public function getUsers()
    {
        return $this->hasOne(Users::classname(), ['id' => 'user_id']);
    }

    public function getSection()
    {
        return $this->hasOne(Sections::classname(), ['id' => 'section_id']);
    }

    public function role($id)
    {
        switch ($id) {
            case '1':
            return 'Admin';
            break;
            case '2':
            return 'Nazoratchi';
            break;
            case '3':
            return 'Sotuvchi';
            break;
            case '4':
            return 'OTK';
            break;
            case '5':
            return 'Bo`lim boshlig';
            break;
            case '6':
            return 'Kroychi';
            break;
            case '7':
            return 'Bo`lim ishchisi';
            break;
            
            
        }
    }
}
