<?php

namespace frontend\modules\api\v1\models;


use common\models\Inquiry;
use common\models\InquiryBrand;
use common\models\InquiryDoctorList;
use common\models\InquiryTreatment;
use common\models\Settings;
use frontend\modules\api\v1\resources\UserProfile;
use linslin\yii2\curl\Curl;
use Yii;
use yii\base\Model;

class GetDoctorList extends Model
{
    public $inquiry_id;

    public function rules()
    {
        return [
            ['inquiry_id','required']
        ];
    }

    public function attributeLabels()
    {
        return [
            'inquiry_id'=>\Yii::t('app','Inquiry Id')
        ];
    }

    /**
     * @return array
     */
//    public function getDoctorList()
//    {
//        $curl = new Curl();
//       // $curl->setOption(CURLOPT_CAINFO, Yii::getAlias('@base') . "../../cacert.pem");
//        $inquiryDoctorList = InquiryDoctorList::findAll([
//            'inquiry_id' => $this->inquiry_id
//        ]);
//
//        if (empty($inquiryDoctorList)) {
//            Inquiry::deleteAll(['id' => $this->inquiry_id]);
//            InquiryBrand::deleteAll(['inquiry_id' => $this->inquiry_id]);
//            InquiryTreatment::deleteAll(['inquiry_id' => $this->inquiry_id]);
//            \Yii::$app->response->setStatusCode(409);
//            return Yii::t('app', 'No doctors are available on your inquiry');
//        }
//
//        $returnData = [];
//        foreach ($inquiryDoctorList as $list) {
//            /* @var $list InquiryDoctorList */
//            $userProfile = UserProfile::findOne(['user_id' => $list->user_id]);
//            if (!empty($returnData[$userProfile->user_id])) {
//                $returnData[$userProfile->user_id]['price'] += $list->price;
//            } else {
////                $result = $curl->get('https://www.zipcodeapi.com/rest/' . Yii::$app->params['zipCodeServiceApiKey'] .'/distance.json/'. $userProfile->zipcode .'/'. Yii::$app->user->identity->userProfile->zipcode .'/mile');
////                $distace_obj = json_decode($result);
////                if (!isset($distace_obj->distance)) {
////                    $distance = Yii::t('app', 'Sorry, unable to calculate');
////                } else {
////                    $distance = round($distace_obj->distance, 2) . ' miles';
////                }
//
//                $returnData[$userProfile->user_id] = [
//                    'doctor_id'=>$userProfile->user_id,
//                    'clinic'=> $list->user->doctor->clinic,
//                    'city' => $list->user->userProfile->city,
//                    //distance' => $distance ,
//                    'photo'=>$userProfile->avatar_path ? $userProfile->avatar_base_url . '/' . $userProfile->avatar_path : false,
//                    'price'=>$list->price,
//                    'rating'=>[
//                        'stars'=>$userProfile->rating,
//                        'reviews'=>$userProfile->reviews
//                    ],
//                    'time_after_create'=> time() - $inquiryDoctorList[0]->inquiry->created_at
//
//                ];
//            }
//
//        }
//        return array_values($returnData);
//    }

    public function getDoctorList()
    {
        $curl = new Curl();
        // $curl->setOption(CURLOPT_CAINFO, Yii::getAlias('@base') . "../../cacert.pem");
//        $inquiryDoctorList = InquiryDoctorList::findAll([
//            'inquiry_id' => $this->inquiry_id
//        ]);

        $inquiryDoctorList = InquiryDoctorList::find()
            ->where(['inquiry_id' => $this->inquiry_id])
            ->with('user')
            ->all();

        if (empty($inquiryDoctorList)) {
            Inquiry::deleteAll(['id' => $this->inquiry_id]);
            InquiryBrand::deleteAll(['inquiry_id' => $this->inquiry_id]);
            InquiryTreatment::deleteAll(['inquiry_id' => $this->inquiry_id]);
            \Yii::$app->response->setStatusCode(409);
            return Yii::t('app', 'No doctors are available on your inquiry');
        }

        $returnData = [];
        foreach ($inquiryDoctorList as $list) {

            /* @var $list InquiryDoctorList */
            //$userProfile = UserProfile::findOne(['user_id' => $list->user_id]);
            if (!empty($returnData[$list->user->id])) {
                $returnData[$list->user->id]['price'] += $list->price;
            } else {
//                $result = $curl->get('https://www.zipcodeapi.com/rest/' . Yii::$app->params['zipCodeServiceApiKey'] .'/distance.json/'. $userProfile->zipcode .'/'. Yii::$app->user->identity->userProfile->zipcode .'/mile');
//                $distace_obj = json_decode($result);
//                if (!isset($distace_obj->distance)) {
//                    $distance = Yii::t('app', 'Sorry, unable to calculate');
//                } else {
//                    $distance = round($distace_obj->distance, 2) . ' miles';
//                }
                $profile = $list->user->userProfile;
                $returnData[$list->user->id] = [
                    'doctor_id'=>$list->user->id,
                    'clinic'=> $list->user->doctor->clinic,
                    'city' => $profile->city,
                    //distance' => $distance ,
                    'photo'=>$profile->avatar_path ? $profile->avatar_base_url . '/' . $profile->avatar_path : false,
                    'price'=>$list->price,
                    'rating'=>[
                        'stars'=>$profile->rating,
                        'reviews'=>$profile->reviews
                    ],
                    'time_after_create'=> time() - $inquiryDoctorList[0]->inquiry->created_at

                ];
            }

        }
        return array_values($returnData);
    }
}