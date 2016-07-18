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

//    public function data()
//    {
//        $inquiryDoctorList = InquiryDoctorList::findAll([
//            'inquiry_id' => $this->inquiry_id,
//            'user_id' => $this->doctor_id
//        ]);
//
//        $id_list = ArrayHelper::map($inquiryDoctorList, 'id', 'id');
//        InquiryDoctorList::updateAll(['is_viewed_by_patient' => InquiryDoctorList::VIEWED_STATUS_YES], ['id' => $id_list]);
//
//        $returnData = [];
//        $list = $inquiryDoctorList[0];
//        /* @var $list InquiryDoctorList */
//        if ($list->inquiry->type == Inquiry::TYPE_BRAND) {
//            foreach ($list->inquiry->inquiryBrands as $brand) {
//                $doctor_offer = InquiryDoctorList::findOne(['user_id' => $this->doctor_id, 'inquiry_id' => $list->inquiry_id, 'param_id'=> $brand->brand_param_id]);
//                if ($doctor_offer) {
//                    if ($brand->brandParam->brand->is_dropdown == 1) {
//                        $doctor_brand = DoctorBrand::findOne(['user_id' => $this->doctor_id, 'brand_param_id' => $brand->brandParam->brand->brandParams[0]->id]);
//                        $price = $doctor_brand->price;
//                        $price = $price * $brand->brandParam->value;
//                        $special_price = $doctor_brand->special_price;
//                        $special_price = !is_null($special_price) ? $special_price * $brand->brandParam->value : null;
//                    } else {
//                        $price = $doctor_offer->price;
//                        $special_price = $doctor_offer->special_price;
//                    }
//                    $reward = !is_null($special_price) ? $special_price * Settings::findOne(Settings::REWARD_AFTER_PAYMENT)->value / 100 : $price * Settings::findOne(Settings::REWARD_AFTER_PAYMENT)->value / 100;
//                    $returnData[$brand->id] = [
//                        'id' => $doctor_offer->id,
//                        'brand' => $brand->brandParam->brand->name,
//                        'price' => $price,
//                        'special_price' => $special_price,
//                        'reward' => $reward
//                    ];
//                    if ($brand->brandParam->brand->per == Brand::PER_SESSION) {
//                        if (is_numeric($brand->brandParam->value)) {
//                            $returnData[$brand->id]['param_value'] = $brand->brandParam->value;
//                        } else {
//                            $returnData[$brand->id]['sessions'] = 1;
//                            $returnData[$brand->id]['param_value'] = $brand->brandParam->value;
//                        }
//                        $returnData[$brand->id]['param_name'] = $brand->brandParam->brand->getPer($brand->brandParam->brand->per);
//
//                    } else {
//                        $returnData[$brand->id]['param_name'] = $brand->brandParam->brand->getPer($brand->brandParam->brand->per);
//                        $returnData[$brand->id]['param_value'] = $brand->brandParam->value;
//
//                    }
//                }
//
//            }
//        } else {
//            foreach ($list->inquiry->inquiryTreatments as $treatment) {
//                /* @var $treatment InquiryTreatment */
//
//                $doctor_offer = InquiryDoctorList::findOne(['user_id' => $this->doctor_id, 'inquiry_id' => $list->inquiry_id, 'param_id'=>$treatment->treatment_param_id]);
//
//                if ($doctor_offer) {
//                    $price = $doctor_offer->price;
//                    $special_price = $doctor_offer->special_price;
//                    $inquiry_treatment = InquiryTreatment::find()->where(['inquiry_id' => $list->inquiry_id])->andWhere(['treatment_param_id' => $treatment->treatmentParam->id])->one();
//                    $is_brand_provided = BrandProvidedTreatment::find()->where(['treatment_param_id' => $treatment->treatmentParam->id])->all();
//                    $brands_array = [];
//                    $type = 'Session';
//                    $count = 0;
//                    if (!is_null($inquiry_treatment->treatment_intensity_id)) {
//                        $treatment_intensity = TreatmentIntensity::find()->where(['id' => $inquiry_treatment->treatment_intensity_id])->all();
//
//                        foreach ($treatment_intensity as $intensity) {
//                            $count += $intensity->count * (isset($treatment->session->session_count) ? $treatment->session->session_count : 1)    ;
//                            $brands_array[] = $intensity->brandParam->brand->name;
//                        }
//
//                        $type = Brand::getPer($treatment_intensity[0]->brandParam->brand->per);
//
//                        $procedure_name =  implode(', ', $brands_array);
//
//                    } elseif (!is_null($inquiry_treatment->severity_id)){
//
//                        $treatment_severity = TreatmentParamSeverity::find()->where(['severity_id' => $inquiry_treatment->severity_id])->andWhere(['param_id' => $treatment->treatment_param_id])->all();
//                        foreach ($treatment_severity as $severity) {
//                            $count += $severity->count;
//                            $brands_array[] = $severity->brandParam->brand->name;
//                        }
//
//                        $type = Brand::getPer($treatment_severity[0]->brandParam->brand->per);
//                        $procedure_name =  implode(', ', $brands_array);
//
//                    } elseif(!empty($is_brand_provided)) {
//
//                        foreach ($is_brand_provided as $item_provided) {
//                            $count += $item_provided->count;
//                            $brands_array[] = $item_provided->brandParam->brand->name;
//                        }
//
//                        $type = Brand::getPer($is_brand_provided[0]->brandParam->brand->per);
//                        $procedure_name =  implode(', ', $brands_array);
//
//                    } else {
//                        $procedure_name = $treatment->treatmentParam->treatment->name;
//                    }
//
//                    if ($count == 0) {
//                        $count = !empty($treatment->session) ? $treatment->session->session_count : 0;
//                    }
//                    $reward = !is_null($special_price) ? $special_price * Settings::findOne(Settings::REWARD_AFTER_PAYMENT)->value / 100 : $price * Settings::findOne(Settings::REWARD_AFTER_PAYMENT)->value / 100;
//                    $returnData[] = [
//                        'id' => InquiryDoctorList::findOne(['inquiry_id' => $list->inquiry_id, 'param_id' => $treatment->treatment_param_id, 'user_id' => $this->doctor_id])->id,
//                        'procedure_name' => $procedure_name,
//                        'param' => $treatment->treatmentParam->value,
//                        'price' => $price,
//                        'special_price' => $special_price,
//                        'param_name' => $type,
//                        'amount' => $count,
//                        'reward' => $reward
//                    ];
//                }
//            }
//        }
//        /** @var UserProfile $userProfile */
//        $userProfile = UserProfile::findOne($list->user->id);
//
//        $photos = $list->user->doctor->doctorPhotos;
//        $photo_array = [];
//        if (!empty($photos)) {
//            foreach ($photos as $photo) {
//                $photo_array[] =  $photo->base_url . '/' . $photo->path;
//            }
//        }
//
//        $data = [
//            'clinic'=> $list->user->doctor->clinic,
//            'photo'=> $userProfile->avatar_path ? $userProfile->avatar_base_url.'/'.$userProfile->avatar_path : false,
//            'photos' =>  $photo_array,
//            'biography' => $list->user->doctor->biography,
//            'address'=>[
//                'zip_code' => $userProfile->zipcode,
//                'state_id' => $userProfile->state_id,
//                'city' => $userProfile->city,
//                'address'=>$userProfile->address
//            ],
//            'rating'=>[
//                'stars'=>$userProfile->rating,
//                'reviews'=>$userProfile->reviews,
//                'mobile_url' => $userProfile->mobile_url
//            ],
//            'data'=>$returnData
//        ];
//        return $data;
//    }

    public function data()
    {
        $inquiryDoctorList = InquiryDoctorList::find()
            ->where(['inquiry_id' => $this->inquiry_id])
            ->with('inquiry')
            ->with('treatmentParam.treatment')
            ->with('user.userProfile')
            ->andWhere(['inquiry_doctor_list.user_id' => $this->doctor_id])
            ->all();
        
        if (!empty($inquiryDoctorList)) {
            $id_list = ArrayHelper::map($inquiryDoctorList, 'id', 'id');

            InquiryDoctorList::updateAll(['is_viewed_by_patient' => InquiryDoctorList::VIEWED_STATUS_YES], ['id' => $id_list]);

            if ($inquiryDoctorList[0]->inquiry->type == Inquiry::TYPE_BRAND) {

                /** @var InquiryDoctorList $doctor_offer */
                foreach ($inquiryDoctorList as $doctor_offer) {
                    unset($returnData);
                    $price = $doctor_offer->price;

                    $returnData[$doctor_offer->user_id] = [
                        'id' => $doctor_offer->id,
                        'brand' => $doctor_offer->brandParam->brand->name,
                        'price' => $price,
                        'reward' => ($price * Settings::findOne(Settings::REWARD_AFTER_PAYMENT)->value / 100)
                    ];

                    if ($doctor_offer->brandParam->brand->per == Brand::PER_SESSION) {


                        if (is_numeric($doctor_offer->brandParam->value)) {
                            $returnData[$doctor_offer->user_id]['param_value'] = $doctor_offer->brandParam->value;
                        } else {
                            $returnData[$doctor_offer->user_id]['sessions'] = 1;
                            $returnData[$doctor_offer->user_id]['param_value'] = $doctor_offer->brandParam->value;
                        }
                        $returnData[$doctor_offer->user_id]['param_name'] = $doctor_offer->brandParam->brand->getPer($doctor_offer->brandParam->brand->per);

                    } else {
                        $returnData[$doctor_offer->user_id]['param_name'] = $doctor_offer->brandParam->brand->getPer($doctor_offer->brandParam->brand->per);
                        $returnData[$doctor_offer->user_id]['param_value'] = $doctor_offer->brandParam->value;

                    }


                    if (!isset($data[$doctor_offer->user_id])) {
                        $data[$doctor_offer->user_id] = [
                            'clinic'=> $doctor_offer->user->doctor->clinic,
                            'doctor' => $doctor_offer->user->userProfile->getFullName(),
                            'photo'=> $doctor_offer->user->userProfile->avatar_path ? $doctor_offer->user->userProfile->avatar_base_url .'/'. $doctor_offer->user->userProfile->avatar_path : false,
                            'address' =>[
                                'zip_code' => $doctor_offer->user->userProfile->zipcode,
                                'state_id' => $doctor_offer->user->userProfile->state_id,
                                'city' => $doctor_offer->user->userProfile->city,
                                'address'=> $doctor_offer->user->userProfile->address
                            ],
                            'data' => $returnData
                        ];
                    } else {
                        $data[$doctor_offer->user_id]['data'][] =  $returnData[$doctor_offer->user_id];
                    }

                }
            } elseif($inquiryDoctorList[0]->inquiry->type == Inquiry::TYPE_TREATMENT) {

                /** @var InquiryTreatment $doctor_offer */
                foreach ($inquiryDoctorList as $doctor_offer) {
                    $is_brand_provided = BrandProvidedTreatment::find()
                        ->where(['treatment_param_id' => $doctor_offer->param_id ])
                        ->with('brandParam.brand')
                        ->all();
                    $returnData = [];
                    $brands_array = [];
                    $price = $doctor_offer->price;
                    $type = 'Session';
                    $count = 0;
                    if ($doctor_offer->inquiryTreatment->treatment_intensity_id) {
                        $treatment_intensity = TreatmentIntensity::find()->where(['id' => $doctor_offer->inquiryTreatment->treatment_intensity_id])->with('brandParam.brand')->all();
                        foreach ($treatment_intensity as $intensity) {
                            $count += $intensity->count * (isset($doctor_offer->treatmentParam->treatment->session->session_count) ? $doctor_offer->treatmentParam->treatment->session->session_count : 1)    ;
                            $brands_array[] = $intensity->brandParam->brand->name;
                        }

                        $type = Brand::getPer($treatment_intensity[0]->brandParam->brand->per);
                        $procedure_name =  implode(', ', $brands_array);

                    }  elseif ($doctor_offer->inquiryTreatment->severity_id){

                        $treatment_severity = TreatmentParamSeverity::find()
                            ->where(['severity_id' => $doctor_offer->inquiryTreatment->severity_id])
                            ->andWhere(['param_id' => $doctor_offer->param_id])
                            ->with('brandParam.brand')
                            ->all();
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
                        $procedure_name = $doctor_offer->treatmentParam->treatment->name;
                    }

                    if ($count == 0) {
                        $count = !empty($doctor_offer->inquiryTreatment->session) ? $doctor_offer->inquiryTreatment->session->session_count : 0;
                    }
                    $userProfile = UserProfile::findOne($doctor_offer->user_id);
                    $returnData = [
                        'id' => $doctor_offer->id,
                        'procedure_name' => $procedure_name,
                        'param' => $doctor_offer->treatmentParam->value,
                        'price' => $price,
                        'param_name' => $type,
                        'amount' => $count,
                        'reward' => ($price * Settings::findOne(Settings::REWARD_AFTER_PAYMENT)->value / 100)
                    ];

                    if (!isset($data)) {
                        $data = [
                            'clinic'=> $doctor_offer->user->doctor->clinic,
                            'photo'=> $userProfile->avatar_path ? $userProfile->avatar_base_url.'/'.$userProfile->avatar_path : false,
                            'biography' => $userProfile->user->doctor->biography,
                            'address'=>[
                                'zip_code' => $userProfile->zipcode,
                                'state_id' => $userProfile->state_id,
                                'city' => $userProfile->city,
                                'address' => $userProfile->address
                            ],
                            'rating'=>[
                                'stars' => $userProfile->rating,
                                'reviews' => $userProfile->reviews
                            ],
                            'data' => []
                        ];
                    }

                    array_push($data['data'], $returnData);
                }
            } else {
                return false;
            }
            return $data;
        }

        return false;

    }
}