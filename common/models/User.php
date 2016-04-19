<?php
namespace common\models;

use cheatsheet\Time;
use common\commands\command\AddToTimelineCommand;
use common\commands\command\SendEmailCommand;
use Yii;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\web\IdentityInterface;
use DrewM\MailChimp\MailChimp;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property string $access_token
 * @property string $oauth_client
 * @property string $oauth_client_user_id
 * @property string $publicIdentity
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $logged_at
 * @property string $password write-only password
 * @property string $activation_token
 *
 * @property \common\models\UserProfile $userProfile
 * @property Payment[] $payments
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 1;

    const ROLE_USER = 'user';
    const ROLE_MANAGER = 'manager';
    const ROLE_ADMINISTRATOR = 'administrator';

    const EVENT_AFTER_SIGNUP = 'afterSignup';
    const EVENT_AFTER_LOGIN = 'afterLogin';

    const MALE = 0;
    const FEMALE = 1;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
            'auth_key' => [
                'class' => AttributeBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'auth_key'
                ],
                'value' => Yii::$app->getSecurity()->generateRandomString()
            ],
            'access_token' => [
                'class' => AttributeBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'access_token'
                ],
                'value' => Yii::$app->getSecurity()->generateRandomString(40)
            ]
        ];
    }

    /**
     * @return array
     */
    public function scenarios()
    {
        return ArrayHelper::merge(
            parent::scenarios(),
            [
                'oauth_create'=>[
                    'oauth_client', 'oauth_client_user_id', 'email', 'username', '!status'
                ]
            ]
        );
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'email'], 'unique'],
            ['email', 'email'],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            [['username'],'filter','filter'=>'\yii\helpers\Html::encode'],
            [['activation_token'], 'string', 'max' => 64]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username' => Yii::t('common', 'Username'),
            'email' => Yii::t('common', 'E-mail'),
            'status' => Yii::t('common', 'Status'),
            'access_token' => Yii::t('common', 'API access token'),
            'created_at' => Yii::t('common', 'Created at'),
            'updated_at' => Yii::t('common', 'Updated at'),
            'logged_at' => Yii::t('common', 'Last login'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserProfile()
    {
        return $this->hasOne(UserProfile::className(), ['user_id'=>'id']);
    }

    public function getDoctor()
    {
        return $this->hasOne(Doctor::className(), ['user_id' => 'id']);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by username or email
     *
     * @param string $login
     * @return static|null
     */
    public static function findByLogin($login)
    {
        return static::find()
            ->where([
                'and',
                ['or', ['username' => $login], ['email' => $login]]
            ])
            ->one();
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        $expire = Time::SECONDS_IN_A_DAY;
        $parts = explode('_', $token);
        $timestamp = (int) end($parts);
        if ($timestamp + $expire < time()) {
            // token expired
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
//            'status' => self::STATUS_ACTIVE
        ]);
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->getSecurity()->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->getSecurity()->generatePasswordHash($password);
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->getSecurity()->generateRandomString() . '_' . time();
    }

    public function generateActivationToken()
    {
        $this->activation_token = Yii::$app->security->generateRandomString(64);
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    /**
     * Returns user statuses list
     * @param mixed $status
     * @return array|mixed
     */
    public static function getStatuses($status = false)
    {
        $statuses = [
            self::STATUS_ACTIVE => Yii::t('common', 'Active'),
            self::STATUS_DELETED => Yii::t('common', 'Deleted')
        ];
        return $status !== false ? ArrayHelper::getValue($statuses, $status) : $statuses;
    }

    /**
     * Creates user profile and application event
     * @param array $profileData
     * @param bool $promo_code
     */
    public function afterSignup($address_info, array $profileData = [],$promo_code = false)
    {
        $this->refresh();
        Yii::$app->commandBus->handle(new AddToTimelineCommand([
            'category' => 'user',
            'event' => 'signup',
            'data' => [
                'public_identity' => $this->getPublicIdentity(),
                'user_id' => $this->getId(),
                'created_at' => $this->created_at
            ]

        ]));
        $profile = new UserProfile();
        $profile->scenario = UserProfile::PATIENT_PROFILE;
        $profile->state_id = State::getStateIdByShortName($address_info['STATE']);
        $profile->city = $address_info['CITY'];
        $profile->zipcode = $profileData['zipcode'];
        $profile->locale = Yii::$app->language;
        $profile->load($profileData, '');
        $this->link('userProfile', $profile);
        $this->trigger(self::EVENT_AFTER_SIGNUP);
        // Default role
        $auth =  Yii::$app->authManager;
        $auth->assign($auth->getRole(User::ROLE_USER), $this->getId());
        Yii::$app->commandBus->handle(new SendEmailCommand([
            'from' => [Yii::$app->params['adminEmail'] => Yii::$app->name],
            'to' => $this->email,
            'subject' => Yii::t('frontend', 'Activation request for {name}', ['name'=>Yii::$app->name]),
            'view' => 'activation',
            'params' => [
                'user' => $this,
                'mailing_address' => getenv('ADMIN_EMAIL'),
                'current_year' => date('Y'),
                'app_name' => Yii::$app->name,
            ]
        ]));
        if ($promo_code) {
            $promoModel = PromoCode::findOne(['text'=>$promo_code]);
            if ($promoModel !== null) {
                $profile->reward = $promoModel->value;
                $profile->update(false);
                $promoCode = new PromoCode();
                $invite_promo = (bool)RegistrationInvite::find()->where(['promo_id' => $promoModel->id])->andWhere(['status' => RegistrationInvite::IS_PENDING])->one();
                if ($invite_promo) {
                    $promoCode->generateAfterRegistrationPromo($promo_code);
                }
                $usedPromoCode = new PromoUsed();
                $usedPromoCode->user_id = $this->id;
                $usedPromoCode->promo_id = $promoModel->id;
                $usedPromoCode->used_while = PromoUsed::USED_WHILE_REGISTRATION;
                $usedPromoCode->counted = PromoUsed::NOT_COUNTED;
                $usedPromoCode->save(false);
            }
        }

        $chimp = new MailChimp(\Yii::$app->params['mailchimpApiKey']);
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $chimp->verify_ssl = false;
        }
        $chimp->post("lists/". \Yii::$app->params['mailChimpPatientList'] ."/members", [
            'email_address' => $this->email,
            'status'        => 'subscribed',
            "merge_fields"=> [
                "FNAME"=> $profile->firstname,
                "LNAME"=> $profile->lastname,
            ]
        ]);
    }

    /**
     * @return string
     */
    public function getPublicIdentity()
    {
        if ($this->userProfile && $this->userProfile->getFullname()) {
            return $this->userProfile->getFullname();
        }
        if ($this->username) {
            return $this->username;
        }
        return $this->email;
    }

    public function isDoctor()
    {
        return (bool)Doctor::find()->where(['user_id' => $this->id])->count();
    }

    /**
     * @inheritdoc
     * @return \common\models\query\UserQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\UserQuery(get_called_class());
    }

    public static function patientCount()
    {
        return self::find()->patientList()->count();
    }

    /**
     * Calculate bonuses for user.
     * @return int
     */
    public function getBonusesCount()
    {
        return $this->userProfile->reward;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPayments()
    {
        return $this->hasMany(Payment::className(), ['user_id' => 'id']);
    }
}
