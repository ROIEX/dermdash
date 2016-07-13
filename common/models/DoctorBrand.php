<?php

namespace common\models;

use common\behaviors\SaveDoctorsBehavior;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;

/**
 * This is the model class for table "doctor_brand".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $brand_param_id
 * @property integer $price
 * @property integer $special_price
 *
 * @property User $user
 */
class DoctorBrand extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'doctor_brand';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'brand_param_id'], 'integer'],
            [['price', 'special_price'], 'double']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'brand_param_id' => Yii::t('app', 'Brand Param ID'),
            'price' => Yii::t('app', 'Price'),
            'special_price' => Yii::t('app', 'Special Price'),
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
    public function getBrandParam()
    {
        return $this->hasOne(BrandParam::className(), ['id' => 'brand_param_id']);
    }

    /**
     * @param $user_id
     * @param $brand_array
     * @param $dropdown_price_array
     * @return bool|int
     * @throws \yii\db\Exception
     * Save doctor selected brands with prices
     */
    public function saveBrands($user_id, $brand_array, $dropdown_price_array, $brand_special, $dropdown_special)
    {
        $doctor_brand_list = [];
        $brand_array = array_filter($brand_array);
        if (!is_null($dropdown_price_array)) {
            $dropdown_price_array = array_filter($dropdown_price_array);
        } else {
            $dropdown_price_array = [];
        }

        if (!empty($brand_array)) {
            foreach (array_filter($brand_array) as $brand_param => $price) {
                $doctor_brand_list[] = [
                    'user_id' => $user_id,
                    'brand_param_id' => $brand_param,
                    'price' => $price,
                    'special_price' => isset($brand_special[$brand_param]) ? $brand_special[$brand_param] : null
                ];
            }
        }

        if (!empty($dropdown_price_array)) {
            $brand_list = Brand::find()->where(['in', 'id', array_keys($dropdown_price_array)])->all();
            foreach ($brand_list as $brand) {
                if (!empty($brand->brandParams)) {
                        $doctor_brand_list[] = [
                            'user_id' => $user_id,
                            'brand_param_id' => $brand->brandParams[0]->id,
                            'price' => $dropdown_price_array[$brand->id],
                            'special_price' => isset($dropdown_special[$brand->id]) ? $dropdown_special[$brand->id] : null
                        ];
                    }
                }
        }
        if (!empty($doctor_brand_list)) {
            return Yii::$app->db->createCommand()->batchInsert(self::tableName(), $this->activeAttributes(), $doctor_brand_list)->execute();
        }
         return false;
    }

    public function updateSelected($model)
    {
        /** @var Doctor $model */
        $this->deleteSelected($model->user_id);
        return $this->saveBrands($model->user_id, $model->brands, $model->dropdown_price, $model->brand_special, $model->dropdown_special_price);
    }

    private function deleteSelected($user_id)
    {
        return self::deleteAll(['user_id' => $user_id]);
    }

    /**
     * @param $user_id
     * @return bool|string
     * Returns list of doctor selected brands
     */
    public static function getSelectedList($user_id)
    {
        $selected_array = self::find()->where(['user_id' => $user_id])->all();
        $selected_list = [];
        if (!empty($selected_array)) {
            foreach($selected_array as $selected_item) {
                $selected_list[] = $selected_item->brandParam->brand->name;
            }
            return implode(', ', array_unique($selected_list));
        }

        return false;
    }

    /**
     * @param $user_id
     * @return array|bool
     */
    public static function getPricedBrands($user_id)
    {
        $selected_array = self::find()->where(['user_id' => $user_id])
            ->with('brandParam.brand')
            ->all();
        if (!empty($selected_array)) {
            /** @var DoctorBrand $selected_item */
            foreach($selected_array as $selected_item) {

                if (isset($selected_item->brandParam->brand)) {
                    if ($selected_item->brandParam->brand->is_dropdown == 1) {
                        $param = '1 ' . Brand::getPer($selected_item->brandParam->brand->per);
                    } else {
                        $param = (($selected_item->brandParam->value == 1 || !is_numeric($selected_item->brandParam->value)) ?
                            ($selected_item->brandParam->value . ((!is_numeric($selected_item->brandParam->value) && $selected_item->brandParam->brand->id != 36)? (", ") : ' ') . ($selected_item->brandParam->brand->id == 36 ? '': Brand::getPer($selected_item->brandParam->brand->per)) . ($selected_item->brandParam->brand->id == 38 ? ", " . Yii::t('app', '2 syringes per session') : '')) :
                            ($selected_item->brandParam->value . " " . Inflector::pluralize(Brand::getPer($selected_item->brandParam->brand->per))) . ($selected_item->brandParam->brand->id == 38 ? ", " . Yii::t('app', '2 syringes per session') : ''));
                    }
                    $selected_list[$selected_item->brandParam->brand->name][] = [
                        'param' => $param,
                        'price' => $selected_item->price . ' $'
                    ];
                }

            }
            return $selected_list;
        }

        return false;
    }

    /**
     * @param $user_id
     * @return bool
     */
    public static function getSelectedListWithPrices($user_id)
    {
        $selected_array = self::find()->where(['user_id' => $user_id])->all();
        $selected_list = [];
        if (!empty($selected_array)) {
            foreach($selected_array as $selected_item) {
                $selected_list[] = $selected_item->brandParam->brand->name;
            }
            return implode(', ', array_unique($selected_list));
        }

        return false;
    }

    /**
     * @param $user_id
     * @return array|bool
     */
    public static function getSelectedIdList($user_id)
    {
        $doctor_param_list = DoctorBrand::findAll(['user_id' => $user_id]);
        if (!empty($doctor_param_list)) {
            foreach ($doctor_param_list as $doctor_param) {
                $brand_param_id_list[$doctor_param->brand_param_id] = $doctor_param->price;
            }
            if (!empty($brand_param_id_list)) {
                return $brand_param_id_list;
            }
            return false;
        }

        return false;
    }

    public static function getSpecialPrices($user_id)
    {
        $doctor_param_list = DoctorBrand::findAll(['user_id' => $user_id]);
        if (!empty($doctor_param_list)) {
            foreach ($doctor_param_list as $doctor_param) {
                $brand_param_id_list[$doctor_param->brand_param_id] = $doctor_param->special_price;
            }
            if (!empty($brand_param_id_list)) {
                return $brand_param_id_list;
            }
            return false;
        }

        return false;
    }
    
    public function getDropdownPrices($user_id)
    {
        $doctor_dropdown_params_prices = [];
        $default_brand_param_list = [];
        $dropdown_brands = Brand::find()->where(['is_dropdown' => 1])->all();
        foreach ($dropdown_brands as $brand) {
            $default_brand_param_list[] = $brand->defaultBrandParam->id;
        }
        $doctor_dropdown_prices_array = DoctorBrand::find()->where(['in', 'brand_param_id', $default_brand_param_list])->andWhere(['user_id' => $user_id])->all();
        foreach ($doctor_dropdown_prices_array as $doctor_price) {
            $doctor_dropdown_params_prices[$doctor_price->brand_param_id] = $doctor_price->price;
        }
        if (!empty($doctor_dropdown_params_prices)) {
            return $doctor_dropdown_params_prices;
        }
        return false;
    }

    public function getDropdownSpecial($user_id)
    {
        $doctor_dropdown_params_prices = [];
        $default_brand_param_list = [];
        $dropdown_brands = Brand::find()->where(['is_dropdown' => 1])->all();
        foreach ($dropdown_brands as $brand) {
            $default_brand_param_list[] = $brand->defaultBrandParam->id;
        }
        $doctor_dropdown_prices_array = DoctorBrand::find()->where(['in', 'brand_param_id', $default_brand_param_list])->andWhere(['user_id' => $user_id])->all();
        foreach ($doctor_dropdown_prices_array as $doctor_price) {
            $doctor_dropdown_params_prices[$doctor_price->brand_param_id] = $doctor_price->special_price;
        }
        if (!empty($doctor_dropdown_params_prices)) {
            return $doctor_dropdown_params_prices;
        }
        return false;
    }
}
