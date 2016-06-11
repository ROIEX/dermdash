<?php

namespace common\models;

use Yii;
use common\components\StatusHelper;
use yii\helpers\ArrayHelper;
use yii\validators\NumberValidator;

/**
 * This is the model class for table "{{%doctor}}".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $clinic
 * @property string $license
 * @property string $doctor_type
 * @property string $fax
 * @property string $add_info
 * @property integer $status
 * @property integer $signature
 * @property integer $website
 * @property integer $biography
 *
 * @property User $user
 * @property DoctorBrand[] $doctorBrands
 * @property DoctorBrand[] $doctorBrandPrice
 * @property UserProfile[] $profile
 */
class Doctor extends \yii\db\ActiveRecord
{
    public $treatments;
    public $brands;
    public $treatment_discounts;
    public $brand_provided_treatments;
    public $dropdown_price;

    const DOCTOR_TYPE_MD = 0;
    const DOCTOR_TYPE_DO = 1;

    const GENDER_MALE = 0;
    const GENDER_FEMALE = 1;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%doctor}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'status', 'doctor_type'], 'integer'],
            [['website', 'signature'], 'required'],
            [['license', 'fax', 'license'], 'string', 'max' => 64],
            [['signature', 'website'], 'string', 'max' => 128],
            ['clinic', 'string', 'max' => 128],
            ['biography', 'string', 'max' => 1000],
            ['add_info', 'string', 'max' => 30],
            ['status', 'default', 'value' => StatusHelper::STATUS_ACTIVE],
            [['treatments', 'brands', 'treatment_discounts', 'brand_provided_treatments', 'dropdown_price'], 'safe'],
            [['brands', 'dropdown_price', 'treatments'], 'checkPrices', 'skipOnEmpty' => true],
           //['brands', 'checkFillers', 'skipOnEmpty' => true],
            ['treatment_discounts', 'checkDiscounts', 'skipOnEmpty' => true],
            ['treatments', 'checkTreatments', 'skipOnEmpty' => true],
            [['brand_provided_treatments'], 'checkBrandProvidedTreatments']
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

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'clinic' => Yii::t('app', 'Clinic'),
            'license' => Yii::t('app', 'License'),
            'fax' => Yii::t('app', 'Fax'),
            'status' => Yii::t('app', 'Status'),
            'doctor_type' => Yii::t('app', 'Doctor type'),
            'treatments' => Yii::t('app', 'Treatments'),
            'brands' => Yii::t('app', 'Brands'),
            'signature' => Yii::t('app', 'Electronic Signature')
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfile()
    {
        return $this->hasOne(UserProfile::className(), ['user_id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDoctorTreatments()
    {
        return $this->hasMany(DoctorTreatment::className(), ['user_id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDoctorBrands()
    {
        return $this->hasMany(DoctorBrand::className(),['user_id'=>'user_id']);
    }

    public function getDoctorBrandPrice($brand_param_id)
    {
        return $this->hasOne(DoctorBrand::className(),['user_id'=>'user_id'])->where(['brand_param_id' => $brand_param_id])->one();

    }

    /**
     * @inheritdoc
     * @return \common\models\query\DoctorQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\DoctorQuery(get_called_class());
    }

    public static function getDoctorType($doctor_type = false)
    {
        $array = [
            self::DOCTOR_TYPE_DO => Yii::t('app','DO'),
            self::DOCTOR_TYPE_MD => Yii::t('app','MD'),
        ];

        return ($doctor_type === false) ? $array : $array[$doctor_type];
    }

    public static function getDoctorCount()
    {
        return self::find()->count();
    }

    public static function getPhoto($base_url, $path)
    {
        return ($base_url && $path)
            ? Yii::getAlias($base_url . '/' . $path)
            : Yii::getAlias('@backendUrl'. '/img/anonymous.jpg');
    }
}