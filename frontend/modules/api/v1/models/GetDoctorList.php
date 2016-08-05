<?php

namespace frontend\modules\api\v1\models;


use common\models\Inquiry as CommonInquiry;
use common\models\InquiryBrand;
use common\models\InquiryDoctorList;
use common\models\InquiryTreatment;
use common\models\User;
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
    public function getDoctorList()
    {
        $curl = new Curl();
        $inquiryDoctorList = InquiryDoctorList::findAll([
            'inquiry_id' => $this->inquiry_id
        ]);

        if (empty($inquiryDoctorList)) {
            CommonInquiry::deleteAll(['id' => $this->inquiry_id]);
            InquiryBrand::deleteAll(['inquiry_id' => $this->inquiry_id]);
            InquiryTreatment::deleteAll(['inquiry_id' => $this->inquiry_id]);
            \Yii::$app->response->setStatusCode(409);
            return Yii::t('app', 'No doctors are available on your inquiry');
        }

        $returnData = [];
        foreach ($inquiryDoctorList as $list) {
            /* @var $list InquiryDoctorList */
            $userProfile = UserProfile::findOne(['user_id' => $list->user_id]);
            if (!empty($returnData[$userProfile->user_id])) {
                $returnData[$userProfile->user_id]['price'] += $list->price;
                $returnData[$userProfile->user_id]['special_price'] += $list->special_price != 0 ? $list->special_price : $list->price;
            } else {
                if (Yii::$app->user->identity->id != User::GUEST_ACCOUNT_ID) {
                    $result = $curl->get('https://www.zipcodeapi.com/rest/' . Yii::$app->params['zipCodeServiceApiKey'] .'/distance.json/'. $userProfile->zipcode .'/'. Yii::$app->user->identity->userProfile->zipcode .'/mile');
                    $distace_obj = json_decode($result);
                } 
              
                if (!isset($distace_obj->distance)) {
                    $distance = Yii::t('app', 'Sorry, unable to calculate');
                } else {
                    $distance = round($distace_obj->distance, 2) . ' miles';
                }

                $photos = $list->user->doctor->doctorPhotos;
                $photo_array = [];
                if (!empty($photos)) {
                    foreach ($photos as $photo) {
                        $photo_array[] =  $photo->base_url . '/' . $photo->path;
                    }
                }


                $returnData[$userProfile->user_id] = [
                    'doctor_id' => $userProfile->user_id,
                    'inquiry_id' => $list->inquiry_id,
                    'clinic'=> $list->user->doctor->clinic,
                    'city' => $list->user->userProfile->city,
                    'distance' => $distance,
                    'photo' => $userProfile->avatar_path ? $userProfile->avatar_base_url . '/' . $userProfile->avatar_path : false,
                    'photos' =>  $photo_array,
                    'price' => $list->price,
                    'special_price' => $list->special_price == $list->price ? '' : $list->special_price,
                    'rating'=> [
                        'stars' => $userProfile->rating,
                        'reviews' => $userProfile->reviews
                    ],
                    'add_info'=> $list->user->doctor->add_info,
                    'time_after_create' => time() - $inquiryDoctorList[0]->inquiry->created_at

                ];
            }

        }
        return array_values($returnData);
    }

}