<?php

namespace common\models;

use common\components\StatusHelper;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;

/**
 * This is the model class for table "doctor_treatment".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $treatment_param_id
 * @property integer $price
 * @property integer $special_price
 * @property integer $treatment_session_id
 *
 * @property User $user
 * @property TreatmentParam $treatmentParam
 * @property TreatmentSession $treatmentSession
 */
class DoctorTreatment extends \yii\db\ActiveRecord
{
    const MIN_SESSION_VALUE = 1;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'doctor_treatment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'treatment_param_id', 'treatment_session_id'], 'integer'],
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
            'price' => Yii::t('app', 'Price'),
            'special_price' => Yii::t('app', 'Special Price'),
            'treatment_param_id' => Yii::t('app', 'Treatment Param ID'),
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
    public function getTreatmentParam()
    {
        return $this->hasOne(TreatmentParam::className(), ['id' => 'treatment_param_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTreatmentSession()
    {
        return $this->hasOne(TreatmentSession::className(), ['id' => 'treatment_session_id']);
    }
    
    /**
     * @param $user_id
     * @param $treatment_array
     * @return bool|int
     * @throws \yii\db\Exception
     * Saving doctor selected treatment params
     */
    public function saveTreatments($user_id, $treatment_param_array, $treatment_discounts, $brand_provided_treatment_array)
    {
        $doctor_treatment_param_list = [];
        if (is_null($brand_provided_treatment_array)) {
            $brand_provided_treatment_array = [];
        }

        $treatment_param_array = array_filter($treatment_param_array);
        $brand_provided_treatment_array = array_filter($brand_provided_treatment_array);

        $trimmed_discounts = [];
        foreach ($treatment_discounts as $treatment_id => $discounts) {
            foreach ($discounts as $discount_id => $discount_value) {
                if (trim($discount_value) != '') {
                    $trimmed_discounts[$treatment_id] = $discounts;
                }
            }
        }
        $treatment_discounts = $trimmed_discounts;

        if (!empty($treatment_param_array)) {
            foreach ($treatment_param_array as $treatment_param_id => $treatment_param_value) {
                $treatment_param = TreatmentParam::findOne(['id' => $treatment_param_id]);
                $doctor_treatment_param_list[] = [
                    'user_id' => $user_id,
                    'treatment_param_id' => $treatment_param_id,
                    'treatment_session_id' => $treatment_param->treatment->defaultSession->id,
                    'price' => $treatment_param_value,
                    'special_price' => null
                ];

                if (array_key_exists($treatment_param->treatment->id, $treatment_discounts)) {
                    foreach ($treatment_discounts[$treatment_param->treatment->id] as $session_id => $discount_value) {
                        $session = TreatmentSession::findOne(['id' => $session_id]);
                        $doctor_treatment_param_list[] = [
                            'user_id' => $user_id,
                            'treatment_param_id' => $treatment_param_id,
                            'treatment_session_id' => $session_id,
                            'price' => (double)($treatment_param_value * (1 - $discount_value / 100)) * $session->session_count,
                            'special_price' => null
                        ];
                    }
                }
            }
        }

        if (!empty($brand_provided_treatment_array)) {
            foreach ($brand_provided_treatment_array as $treatment_id => $value) {
                $treatment = Treatment::findOne(['id' => $treatment_id]);
                $treatment_params = $treatment->treatmentParams;
                if ($treatment->treatmentIntensity) {
                        foreach ($treatment->treatmentSessions as $session) {

                            if ($session->session_count != 1) {
                                $intensity_discounts[] = [
                                    'treatment_id' => $treatment->id,
                                    'session_id' => $session->id,
                                    'discount_value' => $treatment_discounts[$treatment->id][$session->id],
                                    'user_id' => $user_id,
                                ];
                            }
                        }
                        foreach ($treatment_params as $param) {
                            $doctor_treatment_param_list[] = [
                                'user_id' => $user_id,
                                'treatment_param_id' => $param->id,
                                'treatment_session_id' => $treatment->defaultSession->id,
                                'price' => '',
                                'special_price' => null
                            ];
                        }
                        if (!empty($intensity_discounts)) {
                            Yii::$app->db->createCommand()->batchInsert(TreatmentIntensityDiscounts::tableName(), ['treatment_id', 'session_id', 'discount_value', 'user_id'], $intensity_discounts)->execute();
                        }

                } else {
                    foreach ($treatment_params as $param) {
                        $doctor_treatment_param_list[] = [
                            'user_id' => $user_id,
                            'treatment_param_id' => $param->id,
                            'treatment_session_id' => $treatment->defaultSession->id,
                            'price' => '',
                            'special_price' => null
                        ];
                    }
                }

            }
        }

        if (!empty($doctor_treatment_param_list)) {
            return Yii::$app->db->createCommand()->batchInsert(self::tableName(), $this->activeAttributes(), $doctor_treatment_param_list)->execute();
        }
    }

    /**
     * @param $model
     * @return mixed
     */
    public function updateSelected($model)
    {
        $this->deleteSelected($model->user_id);
        return $this->saveTreatments($model->user_id, $model->treatments, $model->treatment_discounts, $model->brand_provided_treatments);
    }

    /**
     * @param $user_id
     * @return int
     */
    private function deleteSelected($user_id)
    {
        self::deleteAll(['user_id' => $user_id]);
        TreatmentIntensityDiscounts::deleteAll(['user_id' => $user_id]);
    }

    /**
     * @param $user_id
     * @return bool|string
     * Returns list of doctor selected treatments
     */
    public static function getSelectedList($user_id)
    {
        $id_list = self::getSelectedIdList($user_id);
        if (is_array($id_list)) {
            $selected_array = Treatment::find()
                ->joinWith('treatmentParams')
                ->where(['in', 'treatment_param.id', array_keys($id_list)])
                ->all();
            $selected_list = [];
            if (!empty($selected_array)) {
                foreach ($selected_array as $selected_item) {
                    $selected_list[] = $selected_item->name;
                }
                return implode(', ', $selected_list);
            }
        }
        return false;
    }

    /**
     * @param $user_id
     * @return array|bool
     * Returns list of selected treatment`s ids
     */
    public static function getSelectedIdList($user_id)
    {
        $doctor_param_list = DoctorTreatment::find()->where(['user_id' => $user_id])
            ->join('LEFT JOIN', 'treatment_session as session', 'session.id = doctor_treatment.treatment_session_id')
            ->andWhere(['session.session_count' => self::MIN_SESSION_VALUE])
            ->all();

        if (!empty($doctor_param_list)) {
            foreach ($doctor_param_list as $doctor_param) {
                $treatment_param_id_list['special_price'][$doctor_param->treatment_param_id] = $doctor_param->special_price;
                $treatment_param_id_list[$doctor_param->treatment_param_id] = $doctor_param->price;

            }
            return $treatment_param_id_list;
        }

        return false;
    }


    public static function getSpecialPrices($user_id)
    {
        $doctor_param_list = DoctorTreatment::find()->where(['user_id' => $user_id])
            ->join('LEFT JOIN', 'treatment_session as session', 'session.id = doctor_treatment.treatment_session_id')
            ->andWhere(['session.session_count' => self::MIN_SESSION_VALUE])
            ->all();

        if (!empty($doctor_param_list)) {
            foreach ($doctor_param_list as $doctor_param) {
                $treatment_param_id_list[$doctor_param->treatment_param_id] = $doctor_param->special_price;

            }
            return $treatment_param_id_list;
        }

        return false;
    }

    public function getSelectedDiscountsArray($user_id)
    {
        $treatment_discounts = [];

        $default_session_param_list = DoctorTreatment::find()->where(['user_id' => $user_id])
            ->join('LEFT JOIN', 'treatment_session as session', 'session.id = doctor_treatment.treatment_session_id')
            ->andWhere(['session.session_count' => self::MIN_SESSION_VALUE])
            ->all();

        $other_session_param_list = DoctorTreatment::find()->where(['user_id' => $user_id])
            ->join('LEFT JOIN', 'treatment_session as session', 'session.id = doctor_treatment.treatment_session_id')
            ->andWhere(['>', 'session.session_count', 1])
            ->all();

        foreach ($default_session_param_list as $default_param) {
            foreach ($other_session_param_list as $next_param) {
                if ($default_param->treatment_param_id == $next_param->treatment_param_id) {
                    $session = TreatmentSession::findOne(['id' => $next_param->treatment_session_id]);
                    $treatment_discounts[$default_param->treatmentParam->treatment->id][$next_param->treatment_session_id] = (1 -(($next_param->price/$session->session_count)/$default_param->price)) * 100;
                }
            }
        }

        $intensity_sessions_list = TreatmentIntensityDiscounts::findAll(['user_id' => $user_id]);
        if (!empty($intensity_sessions_list)) {
            foreach ($intensity_sessions_list as $discount) {
                $treatment_discounts[$discount->treatment_id][$discount->session_id] = $discount->discount_value;
            }
        }

        return $treatment_discounts;
    }
    
    /**
     * @param $user_id
     * @return bool
     */
    public static function getPricedTreatments($user_id)
    {
        $selected_obj_array = self::find()->where(['user_id' => $user_id])
            ->with('treatmentParam.treatment')
            ->with('treatmentSession')
            ->all();
        if (!empty($selected_obj_array)) {
            /** @var DoctorTreatment $selected_item */
            foreach ($selected_obj_array as $selected_item) {
                if (isset($selected_item->treatmentParam->treatment)) {
                    $selected_array[$selected_item->treatmentParam->treatment->name][] = [
                        'param' => $selected_item->treatmentParam->value . ', ' . ($selected_item->treatmentSession->session_count . ' ' .
                                (($selected_item->treatmentSession->session_count > 1 ? Inflector::pluralize(Yii::t('app', 'Session')) : Yii::t('app', 'Session')))),
                        'price' => $selected_item->price != 0 ? ($selected_item->price . ' $') : Yii::t('app', 'Brand Provided')
                    ];
                }
            }

            return $selected_array;
        }

        return false;
    }
}
