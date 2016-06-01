<?php

namespace common\models;

use common\components\dateformatter\FormatDate;

use trntv\filekit\behaviors\UploadBehavior;
use Yii;
use linslin\yii2\curl\Curl;

/**
 * This is the model class for table "user_profile".
 *
 * @property integer $user_id
 * @property integer $locale
 * @property string $firstname
 * @property string $lastname
 * @property string $picture
 * @property string $avatar
 * @property string $avatar_path
 * @property string $avatar_base_url
 * @property integer $gender
 * @property integer $date_of_birth
 * @property integer $reward
 * @property integer $phone
 * @property integer $address
 * @property integer $zipcode
 * @property integer $state_id
 * @property string  $city
 * @property string  $state_notification
 *
 * @property State $state
 */
class UserProfile extends \yii\db\ActiveRecord
{
    const GENDER_MALE = 0;
    const GENDER_FEMALE = 1;

    const SCENARIO_NOTIFICATION = 'notification';
    const PATIENT_PROFILE = 'patient_profile';

    public $uploaded_image;
    public $email;
    public $rating;
    public $reviews;
    public $mobile_url;
    public $address_info;

    public function behaviors()
    {
        return [
            'uploaded_image' => [
                'class' => UploadBehavior::className(),
                'attribute' => 'uploaded_image',
                'pathAttribute' => 'avatar_path',
                'baseUrlAttribute' => 'avatar_base_url'
            ],
            'encryption' => [
                'class' => '\nickcv\encrypter\behaviors\EncryptionBehavior',
                'attributes' => $this->encryptedFields(),
            ],
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_NOTIFICATION] = ['state_notification'];
        $scenarios[self::PATIENT_PROFILE] = ['user_id', 'firstname', 'lastname', 'city', 'state_id', 'zipcode', 'gender', 'date_of_birth', 'reward'];
        return $scenarios;
    }

    /**
     * @return array
     */
    private function encryptedFields()
    {
        return [
            'firstname',
            'lastname',
            'phone',
            'address',
            'city',
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_profile}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'phone', 'address', 'firstname', 'lastname', 'city', 'zipcode', 'state_id'], 'required'],
            [['user_id',  'firstname', 'lastname', 'city', 'zipcode', 'state_id'], 'required', 'on' => 'patient_profile'],
            [['user_id', 'gender', 'state_id', 'state_notification'], 'integer'],
            ['state_notification','in','range'=>[true,false]],
            [['gender'], 'in', 'range'=>[NULL, self::GENDER_FEMALE, self::GENDER_MALE]],
            [['firstname', 'lastname', 'avatar_path', 'avatar_base_url', 'city'], 'string', 'max' => 255],
            ['address', 'string'],
            ['zipcode', 'match', 'pattern' => "/^\d{5}(?:[-\s]\d{4})?$/"],
            ['zipcode', 'checkExistingZip', 'on' => 'patient_profile'],
            ['reward', 'string', 'max' => '8'],
            ['date_of_birth', 'date', 'format' => 'm/d/Y'],
            ['locale', 'default', 'value' => Yii::$app->params['defaultLanguage']],
            ['locale', 'in', 'range' => array_keys(Yii::$app->params['availableLocales'])],
            ['uploaded_image', 'safe'],
            ['phone', 'string'],
            ['email','email']
        ];
    }

    public function checkExistingZip($attribute, $params)
    {
        $address_info = $this->getInfoByZip($this->zipcode);
        if (empty($address_info)) {
            $this->addError($attribute, Yii::t('app','Wrong zip code'));
        } else {
            $this->address_info = $address_info;
        }
    }

    public function beforeSave($insert)
    {
        if ($this->date_of_birth) {
            $this->date_of_birth = date('Y-m-d', strtotime($this->date_of_birth));
        }
        return  parent::beforeSave($insert);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => Yii::t('common', 'User ID'),
            'firstname' => Yii::t('common', 'First name'),
            'lastname' => Yii::t('common', 'Last name'),
            'locale' => Yii::t('common', 'Locale'),
            'uploaded_image' => Yii::t('common', 'Photo'),
            'gender' => Yii::t('common', 'Gender'),
            'date_of_birth' => Yii::t('common', 'Date of birth'),
            'reward' => Yii::t('common', 'Reward'),
            'phone' => Yii::t('common', 'Phone'),
            'address' => Yii::t('common', 'Address'),
            'zipcode' => Yii::t('common', 'Zip code'),
            'city' => Yii::t('common', 'City'),
            'state_id' => Yii::t('common', 'State'),
            'state_notification'=>Yii::t('common','State Notification')
        ];
    }

    public function afterFind()
    {
        parent::afterFind(); // TODO: Change the autogenerated stub
        $this->date_of_birth = FormatDate::AmericanFormat($this->date_of_birth);
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        Yii::$app->session->setFlash('forceUpdateLocale');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function getFullName()
    {
        if ($this->firstname || $this->lastname) {
            return implode(' ', [$this->firstname, $this->lastname]);
        }
        return null;
    }

    public function getAvatar($default = null)
    {
        return $this->avatar_path
            ? Yii::getAlias($this->avatar_base_url . '/' . $this->avatar_path)
            : $default;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getState()
    {
        return $this->hasOne(State::className(), ['id' => 'state_id']);
    }

    public static function getGenderList()
    {
        return [
            self::GENDER_MALE => Yii::t('app','Male'),
            self::GENDER_FEMALE => Yii::t('app','Female'),
        ];
    }

    public static function getGender($gender)
    {
        $arrayGender = self::getGenderList();
        return isset($arrayGender[$gender]) ? $arrayGender[$gender] : null;
    }

    /**
     * Increment bonus.
     * @param $amount
     * @return bool
     */
    public function addBonus($amount)
    {
        if ((int)$amount > 0) {
            $this->reward = $this->reward + $amount;
            return $this->save(false);
        }
        return true;
    }

    /**
     * Decrement bonus.
     * If reward < 0, reward = 0;
     * @param $amount
     * @return bool
     */
    public function removeBonus($amount)
    {
        if ((int)$amount > 0) {
            $this->reward = $this->reward - $amount;
            if ($this->reward < 0) {
                $this->reward = 0;
            }
            return $this->save(false);
        }
        return true;
    }

    /**
     * @param $zip_code
     * @return \SimpleXMLElement[]
     */
    public function getInfoByZip($zip_code)
    {
        $curl = new Curl();
        $array_result = [];
        $result =  $curl->setOption(CURLOPT_POSTFIELDS, http_build_query(array('USZip' => $zip_code)))->post('http://www.webservicex.net/uszip.asmx/GetInfoByZIP');
        $parsed_result = new \SimpleXMLElement($result);
        if (!empty($parsed_result)) {
            $array_result = $this->simpleXmlToArray($parsed_result->Table);
        }

        return $array_result;
    }

    private function simpleXmlToArray($xmlObject)
    {
        $array = array();

        foreach ($xmlObject->children() as $node) {
            if (is_array($node)) {
                $array[$node->getName()] = simplexml_to_array($node);
            } else {
                $array[$node->getName()] = (string) $node;
            }
        }

        return $array;
    }
}
