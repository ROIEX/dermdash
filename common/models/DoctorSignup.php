<?php

namespace common\models;

use DrewM\MailChimp\MailChimp;
use Yii;
use yii\base\Model;
use common\commands\command\AddToTimelineCommand;
use common\components\StatusHelper;
use yii\helpers\ArrayHelper;
use yii\validators\NumberValidator;

class DoctorSignup extends Model
{
    public $email;
    public $password;
    public $password_confirm;
    public $clinic;
    public $license;
    public $fax;
    public $uploaded_image;
    public $firstname;
    public $lastname;
    public $gender;
    public $date_of_birth;
    public $phone;
    public $doctor_type;
    public $terms;
    public $treatments;
    public $brands;
    public $dropdown_price;
    public $treatment_discounts;
    public $brand_provided_treatments;
    public $address;
    public $city;
    public $state_id;
    public $zipcode;
    public $status;
    public $signature;
    public $website;
    public $biography;



    public function attributeLabels()
    {
        return [
            'email' => Yii::t('app', 'Email'),
            'password' => Yii::t('app', 'Password'),
            'password_confirm' => Yii::t('app', 'Confirm Password'),
            'clinic' => Yii::t('app', 'Clinic'),
            'license' => Yii::t('app', 'License'),
            'fax' => Yii::t('app', 'Fax'),
            'firstname' => Yii::t('app', 'First name'),
            'lastname' => Yii::t('app', 'Last name'),
            'gender' => Yii::t('app', 'Gender'),
            'date_of_birth' => Yii::t('app', 'Date of birth'),
            'phone' => Yii::t('app', 'Phone'),
            'doctor_type' => Yii::t('app', 'Doctor type'),
            'terms' => Yii::t('app', 'Terms and Privacy'),
            'treatments' => Yii::t('app', 'Treatments'),
            'brands' => Yii::t('app', 'Brands'),
            'address' => Yii::t('app', 'Address'),
            'city' => Yii::t('app', 'City'),
            'state_id' => Yii::t('app', 'State'),
            'zipcode' => Yii::t('app', 'Zip code'),
            'dropdown_price' => Yii::t('app', 'Price per unit'),
            'signature' => Yii::t('app', 'Electronic signature'),
            'website' => Yii::t('app', 'Website'),
            'biography' => Yii::t('app', 'Biography'),
        ];
    }

    public function rules()
    {
        return [
            ['doctor_type', 'integer'],
            [['firstname', 'lastname', 'website','gender', 'date_of_birth', 'clinic', 'license', 'doctor_type', 'phone', 'password', 'password_confirm', 'state_id', 'zipcode', 'city', 'address', 'signature'], 'required'],
            ['email', 'email'],
            ['email', 'required'],
            ['email', 'unique', 'targetClass' => '\common\models\User'],
            ['password_confirm', 'compare', 'compareAttribute' => 'password', 'message' => Yii::t('app', 'Passwords do not match')],
            ['terms', 'required', 'requiredValue' => 1, 'message' => Yii::t('app', 'Please accepts our Terms and Conditions before you proceed')],
            ['password', 'string', 'min' => 6, 'max' => 18],
            ['gender', 'in', 'range' => [NULL, UserProfile::GENDER_FEMALE, UserProfile::GENDER_MALE]],
            [['doctor_type'], 'in', 'range' => [NULL, Doctor::DOCTOR_TYPE_DO, Doctor::DOCTOR_TYPE_MD]],
            [['firstname', 'lastname', 'address', 'signature'], 'string', 'max' => 128],
            ['city', 'string', 'max' => 16],
            ['clinic', 'string', 'max' => 128],
            ['biography', 'string', 'max' => 1000],
            [['phone', 'fax', 'website'], 'string'],
            ['date_of_birth', 'date', 'format' => 'm/d/Y', 'message' => Yii::t('app', 'Wrong date format')],
            ['uploaded_image', 'safe'],
            ['status', 'default', 'value' => StatusHelper::STATUS_ACTIVE],
            ['zipcode', 'string', 'max' => '5'],
            ['state_id', 'in', 'range' => array_keys(State::getStates())],
            //['brands', 'checkFillers', 'skipOnEmpty' => true],
            [['brands', 'dropdown_price', 'treatments'], 'checkPrices', 'skipOnEmpty' => true],
            ['treatment_discounts', 'checkDiscounts', 'skipOnEmpty' => true],
            ['treatments', 'checkTreatments', 'skipOnEmpty' => true],
            [['brand_provided_treatments'], 'checkBrandProvidedTreatments'],

        ];
    }

    public function checkFillers($attribute, $params)
    {

        if(!empty($this->$attribute)) {
            $trimmed_array = array_filter($this->$attribute, function($item) {
                if (trim($item) != '') {
                    return $item;
                }
            });
        }

        if (!empty($trimmed_array)) {
            $fillers_brands = Brand::getFillerBrandParams();
            $vipeel_params = BrandParam::findAll(['brand_id' => 27]);
            $vipeel_param_ids = ArrayHelper::map($vipeel_params, 'id', 'id');
            $vipeel_params_count = count($vipeel_params);
            $vipeel_prices_counter = 0;
            $empty_price_array = [];
            foreach ($vipeel_param_ids as $param_id) {
                if (isset($this->brands[$param_id]) && $this->brands[$param_id] != '') {
                    $vipeel_prices_counter ++;
                } elseif(isset($this->brands[$param_id]) && $this->brands[$param_id] == '') {
                    $empty_price_array[] = 'brands['. $param_id . ']';
                }
            }

            if ($vipeel_prices_counter != 0) {
                if ($vipeel_prices_counter != $vipeel_params_count) {
                    foreach ($empty_price_array as $item) {
                        $this->addError($item, Yii::t('app', 'Please fill all the prices for ViPeel'));
                    }
                }
            }

            $common_items = array_intersect(array_keys($trimmed_array), array_keys($fillers_brands));

            $brand_params = BrandParam::find()->where(['in', 'id', $common_items])->all();

            foreach ($brand_params as $brand_param) {
                $brand_params = $brand_param->brand->brandParams;

                foreach ($brand_params as $param) {
                    if (isset($this->brands[$param->id]) && $this->brands[$param->id] == '') {
                        $this->addError('brands['. $param->id . ']' , Yii::t('app', 'Please fill all the prices for {name}', ['name' => $param->brand->name]));
                    }
                }
            }
        }
    }

    public function checkTreatments($attribute, $params)
    {
        if(!empty($this->$attribute)) {
            $trimmed_array = array_filter($this->$attribute, function($item) {
                if (trim($item) != '') {
                    return $item;
                }
            });
            if (!empty($trimmed_array)) {
                $treatment_params = TreatmentParam::find()->all();
                $treatment_params = ArrayHelper::index($treatment_params, 'id');

                foreach ($trimmed_array as $id => $treatment) {
                    foreach ($this->treatment_discounts[$treatment_params[$id]->treatment->id] as $key => $discount) {
                        if ($discount == '') {
                            $this->addError('treatment_discounts['. $treatment_params[$id]->treatment->id . '][' . $key . ']' , Yii::t('app', 'Please enter discount percentages for above treatments'));
                        }
                    }
                }
            }
        }
    }

    public function checkPrices($attribute, $params)
    {
        if(!empty($this->$attribute)) {
            $trimmed_array = array_filter($this->$attribute, function($item) {
                if (trim($item) != '') {
                    return $item;
                }
            });

            if (!empty($trimmed_array)) {
                $validator = new NumberValidator();
                $validator->min = 0;
                $validator->max = 9999999;
                foreach ($trimmed_array as $key => $item) {
                    if (!$validator->validate($item)) {
                        if ($attribute == 'treatments') {
                            $this->addError('treatments[' . $key . ']', Yii::t('app', 'Treatment price must be numeric, more than 0 and less than 9999999'));
                        } else {
                            $this->addError('brands[' . $key . ']', Yii::t('app', 'Brand price must be numeric, more than 0 and less than 9999999'));
                        }
                    }
                }
            }
        }
    }

    public function checkDiscounts($attribute, $params)
    {
        if(!empty($this->$attribute)) {
            $validator = new NumberValidator();
            $validator->min = 0;
            $validator->max = 99;

            foreach ($this->$attribute as $treatment_id => $treatment_discount) {
                $treatment_discount_counter = 0;
                $session_counter = 0;
                $has_filled_value = false;

                foreach ($treatment_discount as $discount_id => $value) {
                    $session_counter ++;
                    if ($value != '') {
                        $has_filled_value = true;
                        if (!$validator->validate($value)) {
                            $this->addError('treatment_discounts['. $treatment_id . '][' . $discount_id . ']', Yii::t('app', 'Discount must be numeric and less than 99'));
                        }
                        $treatment_discount_counter ++;

                    } else {
                        $no_discount_input = 'treatment_discounts['. $treatment_id . '][' . $discount_id . ']';
                    }

                    if ($has_filled_value && $treatment_discount_counter != $session_counter) {
                        $this->addError($no_discount_input, Yii::t('app', 'Please fill all the discounts'));

                    }
                }

                if($has_filled_value && $treatment_discount_counter == $session_counter) {
                    $discounted_treatment = Treatment::findOne(['id' => $treatment_id]);
                    $has_prices = false;
                    foreach ($discounted_treatment->treatmentParams as $param) {
                        if (isset($this->treatments[$param->id]) && $this->treatments[$param->id] != '') {
                            $has_prices = true;
                        } elseif(isset($this->brand_provided_treatments[$discounted_treatment->id]) && $this->brand_provided_treatments[$discounted_treatment->id] != '') {
                            $has_prices = true;
                        }
                    }

                    if (!$has_prices) {
                        if (isset($this->treatments[$discounted_treatment->treatmentParams[0]->id])) {
                            $this->addError('treatments[' . $discounted_treatment->treatmentParams[0]->id . ']', Yii::t('app', 'Please fill the prices for entered discounts'));
                        } else {
                            $this->addError('brand_provided_treatments[' . $discounted_treatment->id . ']', Yii::t('app', 'Please fill the prices for entered discounts'));
                        }

                    }
                }
            }
        }
    }

    public function checkBrandProvidedTreatments($attribute, $params)
    {
        $trimmed_brand_provided = array_filter($this->$attribute, function ($item) {
            if ($item == 1) {
                return $item;
            }
        });

        if (!empty($trimmed_brand_provided)) {

            foreach ($trimmed_brand_provided as $treatment_id => $is_checked) {

                if (isset($this->treatment_discounts[$treatment_id])) {
                    foreach ($this->treatment_discounts[$treatment_id] as $session_id => $discount_value) {

                        if ($discount_value == '') {
                            $this->addError('treatment_discounts[' . $treatment_id . '][' . $session_id . ']', Yii::t('app', 'You must add discount values to checked treatments'));
                        }
                    }
                }


//                $intensity_items = TreatmentIntensity::find()->where(['treatment_id' => $treatment_id])->all();
//                if (!empty($intensity_items)) {
//                    foreach ($intensity_items as $intensity_item) {
//                        if ($this->brands[$intensity_item->brand_param_id] == '') {
//                            $this->addError('brands[' . $intensity_item->brand_param_id . ']', Yii::t('app', 'You must add required brand prices for selected treatment'));
//                        }
//                    }
//                }
//
//                $treatment_params = TreatmentParam::find()->where(['treatment_id' => $treatment_id])->all();
//
//                foreach ($treatment_params as $param) {
//
//                    $severity_items = TreatmentParamSeverity::find()->where(['param_id' => $param->id])->andWhere(['is not', 'brand_param_id', null])->all();
//                    $brand_provided_items = BrandProvidedTreatment::find()->where(['treatment_param_id' => $param->id])->all();
//
//                    if (!empty($severity_items)) {
//                        $brands_first_params_list = [];
//                        foreach ($severity_items as $severity_item) {
//                            $brands_first_params_list[] = BrandParam::findOne($severity_item->brand_param_id)->brand->id;
//                        }
//                        $brands_first_params_list = array_unique($brands_first_params_list);
//
//                        foreach ($brands_first_params_list as $key => $brand_id) {
//
//                            if (isset($this->dropdown_price[$brand_id])) {
//                                if ($this->dropdown_price[$brand_id] == '') {
//                                    $this->addError('dropdown_price[' . $brand_id . ']', Yii::t('app', 'You must add required brand prices for selected treatment'));
//                                }
//                            } else {
//
//                                $brand_id = Brand::findOne([$brand_id])->brandParams[0]->id;
//                                if (isset($this->brands[$brand_id])) {
//                                    if ($this->brands[$brand_id] == '') {
//                                        $this->addError('brands[' . $brand_id . ']', Yii::t('app', 'You must add required brand prices for selected treatment'));
//                                    }
//                                }
//                            }
//                        }
//
//                    }
//
//                    if (!empty($brand_provided_items)) {
//                        foreach ($brand_provided_items as $brand_provided_item) {
//                            if ($this->brands[$brand_provided_item->brand_param_id] == '') {
//                                $this->addError('brands[' . $brand_provided_item->brand_param_id . ']', Yii::t('app', 'You must add required brand prices for selected treatment'));
//                            }
//                        }
//                    }
//
//                }
            }
        }
    }

    public function save()
    {
        if ($this->validate()) {

            $user = new User();

            $user->load($this->attributes, '');
            $user->username = $user->email;
            $user->setPassword($this->password);
            if(!$user->save()) {
                return false;
            }

            $user_profile = new UserProfile();
            $user_profile->load($this->attributes, '');
            $user_profile->user_id = $user->id;
            if (!$user_profile->save()) {

                $user->delete();
                return false;
            }

            $doctor = new Doctor();
            $doctor->load($this->attributes, '');
            $doctor->user_id = $user->id;
            if (!$doctor->save(false)) {
                $user->delete();
                $user_profile->delete();
                return false;
            }

            if($this->afterSignup($user, $doctor)) {
                Yii::$app->getUser()->login($user);
                return true;
            }

        } else {
            return false;
        }
    }

    public function afterSignup($user, $doctor)
    {
        Yii::$app->commandBus->handle(new AddToTimelineCommand([
            'category' => 'user',
            'event' => 'signup',
            'data' => [
                'public_identity' => $user->email,
                'user_id' => $user->id,
                'created_at' => $user->created_at
            ]
        ]));

        Yii::$app->mailer->compose('doctor_registration_admin', ['doctor' => $doctor])
            ->setTo(getenv('ADMIN_EMAIL'))
            ->setSubject('New doctor registration')
            ->send();

        $chimp = new MailChimp(\Yii::$app->params['mailchimpApiKey']);
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $chimp->verify_ssl = false;
        }

        $chimp->post("lists/". \Yii::$app->params['mailChimpDoctorList'] . "/members", [
            'email_address' => $user->email,
            'status' => 'subscribed',
            "merge_fields"=> [
                "FNAME" => $user->userProfile->firstname,
                "LNAME" => $user->userProfile->lastname,
                "COMPANY" => $doctor->clinic,
                "PHONE" => $user->userProfile->phone
            ]
        ]);

        $auth = Yii::$app->authManager;
        $auth->assign($auth->getRole(User::ROLE_MANAGER), $user->id);
        return true;
    }

    public function validationArray()
    {
        array_filter($this->errors, function(&$item) {
            $item =  array_unique($item);
        });

    }
}