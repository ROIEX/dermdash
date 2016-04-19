<?php
namespace backend\models;

use yii\base\Model;
use Yii;
use common\models\User;

/**
 * Account form
 */
class AccountForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $old_password;
    public $password_confirm;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['old_password', 'password', 'password_confirm'], 'required'],
            [['password', 'old_password'], 'string' , 'min' => 6, 'max' => 18],
            [['password_confirm'], 'compare', 'compareAttribute' => 'password']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username' => Yii::t('backend', 'Username'),
            'email' => Yii::t('backend', 'Email'),
            'password' => Yii::t('backend', 'Password'),
            'old_password' => Yii::t('backend', 'Old password'),
            'password_confirm' => Yii::t('backend', 'Password Confirm')
        ];
    }

    public function validatePassword()
    {
        if (!$this->hasErrors()) {
            $user = Yii::$app->user->identity;
            if (!$user || !$user->validatePassword($this->old_password)) {
                $this->addError('old_password', Yii::t('backend', 'Incorrect old password.'));
                return false;
            }
            return true;
        }
    }
}
