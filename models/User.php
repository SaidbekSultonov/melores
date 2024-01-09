<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;


/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property integer $company_id
 * @property string $username
 * @property string $password
 * @property boolean $status
 */
class User extends ActiveRecord implements IdentityInterface
{
    /**
     * @inheritdoc
     */

    public $newpass;
    public $repassword;

    public static function tableName()
    {
        return 'public.user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'password'], 'required','message' => "Maydonni to'ldirish majburiy!"],
            [['username'], 'unique'],
            [['status'], 'boolean'],
            [['username', 'password'], 'string', 'max' => 60],
            [['repassword', 'newpass'], 'required', 'on' => 'changepassword', 'message' => "Maydonni to'ldirish majburiy!"],
            ['newpass', 'editpassword', 'on' => 'changepassword'],
        ];
    }
    
    public function Editpassword()
    {
        if( $this->repassword != $this->newpass)
            return $this->addError('repassword', "Kiritilgan parol mos emas!");
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'company_id' => 'Company ID',
            // 'username' => \Yii::t('Teacher', "Foydalanuvchi Nomi"),
            // 'password' => \Yii::t('Teacher', "Parol"),
            'status' => 'Holati',
        ];
    }


    public function validatePassword($password)
    {
        return $this->password === sha1($password);
    }

    public static function findIdentity($id)
    {
        return User::findOne($id);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUsername()
    {
        return \Yii::$app->user->identity->username;
    }
    
    public static function findIdentityByAccessToken($token, $type = null)
    {

    }

    public static function findByUsername($username) 
    {
        $user = self::find()->where(['username' => $username])->one();
        if (!$user) {
            return null;
        }
        return new static($user);
    }

    public function getAuthKey()
    {
        // return $this->authKey;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        // return $this->authKey === $authKey;
    }
    
}
