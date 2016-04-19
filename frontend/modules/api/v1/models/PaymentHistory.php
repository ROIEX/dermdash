<?php

namespace frontend\modules\api\v1\models;


use common\models\Inquiry;
use common\models\InquiryDoctorList;
use frontend\modules\api\v1\resources\UserProfile;
use Yii;
use yii\base\Model;

class PaymentHistory extends Model
{
    public static function getHistory()
    {
        $returnData = [];
        $inquiries = Inquiry::find()
            ->joinWith('inquiryDoctorLists')
            ->where(['inquiry.user_id'=>getMyId()])
            ->andWhere(['inquiry_doctor_list.status'=>InquiryDoctorList::STATUS_FINALIZED])
            ->orderBy('inquiry_doctor_list.paid_at DESC')
            ->all();

        foreach ($inquiries as $inquiry) {
            foreach ($inquiry->finalizedInquiry as $finalized) {
                $userProfile = UserProfile::findOne($finalized->user_id);
                $procedureName = '';
                if ($inquiry->type == $inquiry::TYPE_TREATMENT) {
                    $item = $inquiry->inquiryTreatments[0]->treatmentParam->treatment;
                    $procedureName = $item->name;
                } else {
                    $procedureName = $inquiry->inquiryBrands[0]->brandParam->brand->name;
                }
                $returnData[] = [
                    'doctor_photo'=> $userProfile->avatar_path ? $userProfile->avatar_base_url . '/' . $userProfile->avatar_path : false,
                    'doctor_name'=>$userProfile->firstname,
                    'doctor_surname'=>$userProfile->lastname,
                    'price'=>$finalized->price,
                    'created_at'=>$inquiry->created_at,
                    'paid_at'=>$finalized->paid_at,
                    'procedure_name'=>$procedureName,
                    'rating'=>[
                        'stars'=>$userProfile->rating,
                        'reviews'=>$userProfile->reviews
                    ]
                ];
            }
        }
            return $returnData;
    }
}