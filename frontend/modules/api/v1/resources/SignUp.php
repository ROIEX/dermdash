<?php
/**
 * Created by PhpStorm.
 * User: rexit
 * Date: 29.12.15
 * Time: 12:39
 */

namespace frontend\modules\api\v1\resources;


use common\models\PromoCode;
use common\models\PromoUsed;
use common\models\RegistrationInvite;
use common\models\State;
use common\models\User;
use common\models\UserProfile;
use Yii;
use yii\base\Model;

class SignUp extends Model
{
    public $username;
    public $email;
    public $password;
    public $firstname;
    public $lastname;
    public $date_of_birth;
    public $gender;
    public $rules_accept;
    public $telemedicine_accept;
    public $promo_code;
    public $zipcode;
    public $address_info;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'filter', 'filter' => 'trim'],
            [['firstname','lastname','gender','date_of_birth','rules_accept','telemedicine_accept', 'username', 'zipcode', 'email', 'password'],'required'],
            ['username', 'unique',
                'targetClass'=>'\common\models\User',
                'message' => Yii::t('frontend', 'This username has already been taken.')
            ],
            ['username', 'string', 'min' => 2, 'max' => 255],
            ['promo_code','string'],
            ['zipcode', 'match', 'pattern' => "/^\d{5}(?:[-\s]\d{4})?$/"],
            ['zipcode', 'checkExistingZip'],
            ['promo_code','checkPromo'],
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'email'],
            ['email', 'unique',
                'targetClass'=> '\common\models\User',
                'message' => Yii::t('frontend', 'This email address has already been taken.')
            ],
            ['password', 'string', 'min' => 6],
            ['gender','in','range'=>[User::MALE,User::FEMALE]],
            ['date_of_birth', 'date', 'format' => 'm/d/Y'],

        ];
    }

    public function checkExistingZip($attribute, $params)
    {
        $profile = new UserProfile();
        $address_info = $profile->getInfoByZip($this->zipcode);
        if (empty($address_info)) {
            $this->addError($attribute, Yii::t('app','Wrong zip code'));
        } else {
            $state = State::getStateIdByShortName($address_info['STATE']);

            if (!$state) {
                $this->addError($attribute, Yii::t('app', 'Sorry, we can`t find your state'));
            } else {
                $this->address_info = $address_info;
            }
        }
    }

    public function checkPromo($attribute, $params)
    {
        if ($this->promo_code) {
            $promo = PromoCode::findOne(['text'=>$this->promo_code]);

            if (!is_null($promo)) {

                if ($promo->is_reusable == PromoCode::SINGLE_USE) {

                    $check_if_used = PromoUsed::findOne(['promo_id' => $promo->id]);
                    if ($check_if_used) {
                        $this->addError($attribute,Yii::t('app', 'This Promo Code can only be used once'));
                    }
                }
            } else {
                $this->addError($attribute,Yii::t('app','Incorrect Promo Code'));
            }
            /* @var $promo PromoCode */
        }
    }

    public function attributeLabels()
    {
        return [
            'username'=>Yii::t('frontend', 'Username'),
            'email'=>Yii::t('frontend', 'E-mail'),
            'password'=>Yii::t('frontend', 'Password'),
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        foreach (Yii::$app->request->post() as $attribute => $value) {
            $this->$attribute = $value;
        }
        if ($this->validate()) {
            $user = new User();
            $user->username = $this->email;
            $user->email = $this->email;
            $user->setPassword($this->password);
            $user->generateActivationToken();
            $user->save();
            $user->afterSignup($this->address_info, $this->attributes, $this->promo_code);
            return $user;
        }
        return null;
    }
}