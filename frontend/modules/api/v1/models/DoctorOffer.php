<?php

namespace frontend\modules\api\v1\models;


use common\models\Brand;
use common\models\BrandProvidedTreatment;
use common\models\DoctorBrand;
use common\models\DoctorTreatment;
use common\models\Inquiry;
use common\models\InquiryDoctorList;
use common\models\InquiryTreatment;
use common\models\Settings;
use common\models\TreatmentIntensity;
use common\models\TreatmentParamSeverity;
use common\models\TreatmentSession;
use frontend\modules\api\v1\resources\UserProfile;
use Yii;
use yii\base\Model;
use yii\helpers\Inflector;
use yii\helpers\ArrayHelper;

class DoctorOffer extends Model
{
    public $doctor_id;
    public $inquiry_id;

    public function rules()
    {
        return [
            [['doctor_id','inquiry_id'],'required'],
            [['doctor_id','inquiry_id'],'integer']
        ];
    }

    public function data()
    {
        $inquiryDoctorList = InquiryDoctorList::findAll([
            'inquiry_id' => $this->inquiry_id,
            'user_id'=>$this->doctor_id
        ]);

        $id_list = ArrayHelper::map($inquiryDoctorList, 'id', 'id');
        InquiryDoctorList::updateAll(['is_viewed_by_patient' => InquiryDoctorList::VIEWED_STATUS_YES], ['id' => $id_list]);

        $returnData = [];
        $list = $inquiryDoctorList[0];
        /* @var $list InquiryDoctorList */
        if ($list->inquiry->type == Inquiry::TYPE_BRAND) {
            foreach ($list->inquiry->inquiryBrands as $brand) {
                $doctor_offer = InquiryDoctorList::findOne(['user_id' => $this->doctor_id, 'inquiry_id' => $list->inquiry_id, 'param_id'=> $brand->brand_param_id]);
                if ($doctor_offer) {
                    if ($brand->brandParam->brand->is_dropdown == 1) {
                        $price = DoctorBrand::findOne(['user_id' => $this->doctor_id, 'brand_param_id' => $brand->brandParam->brand->brandParams[0]->id])->price;
                        $price = $price * $brand->brandParam->value;
                    } else {
                        $price = $doctor_offer->price;
                    }

                    $returnData[$brand->id] = [
                        'id'=>$doctor_offer->id,
                        'brand'=>$brand->brandParam->brand->name,
                        'price'=> $price,
                        'reward'=>($price * Settings::findOne(Settings::REWARD_AFTER_PAYMENT)->value / 100)
                    ];
                    if ($brand->brandParam->brand->per == Brand::PER_SESSION) {
                        if (is_numeric($brand->brandParam->value)) {
                            $returnData[$brand->id]['param_value'] = $brand->brandParam->value;
                        } else {
                            $returnData[$brand->id]['sessions'] = 1;
                            $returnData[$brand->id]['param_value'] = $brand->brandParam->value;
                        }
                        $returnData[$brand->id]['param_name'] = $brand->brandParam->brand->getPer($brand->brandParam->brand->per);

                    } else {
                        $returnData[$brand->id]['param_name'] = $brand->brandParam->brand->getPer($brand->brandParam->brand->per);
                        $returnData[$brand->id]['param_value'] = $brand->brandParam->value;

                    }
                }

            }
        } else {
            foreach ($list->inquiry->inquiryTreatments as $treatment) {
                /* @var $treatment InquiryTreatment */
                $doctor_offer = InquiryDoctorList::findOne(['user_id' => $this->doctor_id, 'inquiry_id' => $list->inquiry_id, 'param_id'=>$treatment->treatment_param_id]);



                if ($doctor_offer) {
                    $price = $doctor_offer->price;
                    $inquiry_treatment = InquiryTreatment::find()->where(['inquiry_id' => $list->inquiry_id])->andWhere(['treatment_param_id' => $treatment->treatmentParam->id])->one();
                    $is_brand_provided = BrandProvidedTreatment::find()->where(['treatment_param_id' => $treatment->treatmentParam->id])->all();
                    $brands_array = [];
                    $type = 'Session';
                    $count = 0;
                    if (!is_null($inquiry_treatment->treatment_intensity_id)) {
                        $treatment_intensity = TreatmentIntensity::find()->where(['id' => $inquiry_treatment->treatment_intensity_id])->all();

                        foreach ($treatment_intensity as $intensity) {
                            $count += $intensity->count * (isset($treatment->session->session_count) ? $treatment->session->session_count : 1)    ;
                            $brands_array[] = $intensity->brandParam->brand->name;
                        }

                        $type = Brand::getPer($treatment_intensity[0]->brandParam->brand->per);

                        $procedure_name =  implode(', ', $brands_array);

                    } elseif (!is_null($inquiry_treatment->severity_id)){

                        $treatment_severity = TreatmentParamSeverity::find()->where(['severity_id' => $inquiry_treatment->severity_id])->andWhere(['param_id' => $treatment->treatment_param_id])->all();
                        foreach ($treatment_severity as $severity) {
                            $count += $severity->count;
                            $brands_array[] = $severity->brandParam->brand->name;
                        }

                        $type = Brand::getPer($treatment_severity[0]->brandParam->brand->per);
                        $procedure_name =  implode(', ', $brands_array);

                    } elseif(!empty($is_brand_provided)) {

                        foreach ($is_brand_provided as $item_provided) {
                            $count += $item_provided->count;
                            $brands_array[] = $item_provided->brandParam->brand->name;
                        }

                        $type = Brand::getPer($is_brand_provided[0]->brandParam->brand->per);
                        $procedure_name =  implode(', ', $brands_array);

                    } else {
                        $procedure_name = $treatment->treatmentParam->treatment->name;
                    }

                    if ($count == 0) {
                        $count = !empty($treatment->session) ? $treatment->session->session_count : 0;
                    }

                    $returnData[] = [
                        'id' => InquiryDoctorList::findOne(['inquiry_id' => $list->inquiry_id, 'param_id' => $treatment->treatment_param_id, 'user_id' => $this->doctor_id])->id,
                        'procedure_name' => $procedure_name,
                        'param' => $treatment->treatmentParam->value,
                        'price' => $price,
                        'param_name' => $type,
                        'amount' => $count,
                        'reward' => ($price * Settings::findOne(Settings::REWARD_AFTER_PAYMENT)->value / 100)
                    ];
                }
            }

        }
        /** @var UserProfile $userProfile */
        $userProfile = UserProfile::findOne($list->user->id);
        $data = [
            'clinic'=> $list->user->doctor->clinic,
            'photo'=> $userProfile->avatar_path ? $userProfile->avatar_base_url.'/'.$userProfile->avatar_path : false,
            'address'=>[
                'zip_code' => $userProfile->zipcode,
                'state_id' => $userProfile->state_id,
                'city' => $userProfile->city,
                'address'=>$userProfile->address
            ],
            'rating'=>[
                'stars'=>$userProfile->rating,
                'reviews'=>$userProfile->reviews
            ],
            'data'=>$returnData
        ];
        return $data;
    }
}