<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%inquiry}}".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $type
 * @property integer $created_at
 * @property integer $comment
 * @property integer $is_viewed
 * @property integer $is_viewed_by_admin
 *
 * @property User $user
 * @property InquiryBrand[] $inquiryBrands
 * @property InquiryTreatment[] $inquiryTreatments
 * @property InquiryDoctorList $doctorAccepted
 * @property InquiryDoctorList[] $inquiryDoctorLists
 * @property InquiryDoctorList[] $existingDoctorOffer
 * @property Payment[] $payment
 */
class Inquiry extends \yii\db\ActiveRecord
{
    const TYPE_TREATMENT = 1;
    const TYPE_BRAND = 2;

    const IS_NOT_VIEWED = 0;
    const IS_VIEWED = 1;

    const STATUS_PENDING = 0;
    const STATUS_COMPLETED = 1;
    const STATUS_ABANDONED = 2;
    const STATUS_REFUNDED = 3;

    const INQUIRY_DAYS_ACTIVE = 2;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%inquiry}}';
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
                ]
            ],
            [
                'class'=>BlameableBehavior::className(),
                'createdByAttribute' => 'user_id',
                'updatedByAttribute' => false,
            ],

        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'type', 'created_at', 'is_viewed', 'is_viewed_by_admin'], 'integer'],
            ['type', 'in', 'range' => [self::TYPE_BRAND, self::TYPE_TREATMENT]],
            ['comment', 'string'],
            ['is_viewed', 'default', 'value' => self::IS_NOT_VIEWED],
            ['is_viewed_by_admin', 'default', 'value' => self::IS_NOT_VIEWED],
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
            'type' => Yii::t('app', 'Type'),
            'created_at' => Yii::t('app', 'Created At'),
            'comment' => Yii::t('app', 'Comment'),
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
    public function getInquiryBrands()
    {
        return $this->hasMany(InquiryBrand::className(), ['inquiry_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExistingDoctorOffer()
    {
        return $this->hasMany(InquiryDoctorList::className(), ['inquiry_id' => 'id'])->where(['user_id' => Yii::$app->user->id]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInquiryDoctorList()
    {
        return $this->hasMany(InquiryDoctorList::className(), ['inquiry_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInquiryTreatments()
    {
        return $this->hasMany(InquiryTreatment::className(), ['inquiry_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPayment()
    {
        return $this->hasOne(Payment::className(), ['inquiry_id' => 'id']);
    }

    /**
     * @return $this
     */
    public function getDoctorAccepted()
    {
        return $this->hasOne(InquiryDoctorList::className(), ['inquiry_id' => 'id'])->where(['status'=>InquiryDoctorList::STATUS_FINALIZED]);
    }

    public function getInquiryDoctorLists()
    {
        return $this->hasMany(InquiryDoctorList::className(), ['inquiry_id' => 'id']);
    }

    /**
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getDoctorIsParticipant()
    {
        return $this->hasOne(InquiryDoctorList::className(), ['inquiry_id' => 'id'])->where(['user_id' => Yii::$app->user->id]);
    }

    /**
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getInquiryDoctorAnswer()
    {
        return $this->hasMany(InquiryDoctorList::className(), ['inquiry_id' => 'id'])->where(['status' => InquiryDoctorList::STATUS_ANSWER_YES])->all();
    }

    /**
     * @return $this
     */
    public function getFinalizedInquiry()
    {
        return $this->hasMany(InquiryDoctorList::className(), ['inquiry_id' => 'id'])->where([InquiryDoctorList::tableName() . '.' .'status' => InquiryDoctorList::STATUS_FINALIZED]);
    }

    /**
     * @return array
     */
    public static function getCureTypeArray()
    {
        $array = [
            self::TYPE_BRAND => Yii::t('app', 'Brand'),
            self::TYPE_TREATMENT => Yii::t('app', 'Treatment'),
        ];
        return $array;
    }

    /**
     * @param $type
     * @return mixed
     */
    public static function getCureType($type)
    {
        $array = self::getCureTypeArray();
        return $array[$type];
    }

    /**
     * @return mixed|string
     */
    public function getInquiryCureType()
    {
        if ($this->inquiryTreatments) {
            return self::getCureType(self::TYPE_TREATMENT);
        } elseif ($this->inquiryBrands) {
            return self::getCureType(self::TYPE_BRAND);
        } else {
            return '';
        }
    }

    /**
     * @return string
     */
    public function getInquiryItem()
    {
        if ($this->inquiryTreatments) {
            return $this->inquiryTreatments[0]->treatmentParam->treatment->name;
        } elseif ($this->inquiryBrands) {
            return $this->inquiryBrands[0]->brandParam->brand->name;
        } else {
            return '';
        }
    }

    /**
     * @return array|string
     */
    public function getInquiryParams()
    {
       $params = [];
        if ($this->inquiryTreatments) {
            foreach ($this->inquiryTreatments as $treatment_param) {
                $params[$treatment_param->id]['value'] = $treatment_param->treatmentParam->value;
                if ($treatment_param->additional_attribute_id) {
                    $params[$treatment_param->id]['additional_attribute'] = $treatment_param->additionalAttribute->value;
                }
            }
            return $params;
        } elseif ($this->inquiryBrands) {
            foreach ($this->inquiryBrands as $brand_param) {
                $params[$brand_param->id]['value'] = $brand_param->brandParam->value;
            }
            return $params;
        } else {
            return false;
        }
    }

    /**
     * @param $date
     * @return bool
     */
    public function checkExpired($date)
    {
        $secondInDay = time() - 3600 * 24 * self::INQUIRY_DAYS_ACTIVE;
        if ($date >= $secondInDay) {
            return false;
        }
        return true;
    }

    /**
     * @return $this
     */
    public function getPendingInquiryList()
    {
        $days_ago_date = time() - 3600 * 24 * self::INQUIRY_DAYS_ACTIVE;
        return $this->find()
           ->join('LEFT JOIN', 'inquiry_doctor_list as list', 'list.inquiry_id = inquiry.id')
            ->where(['and',

                ['>=', 'inquiry.created_at', $days_ago_date],
                ['not in', 'list.inquiry_id',$this->getFinalizedInquiryListId()],

            ]);

    }

    /**
     * @return $this
     */
    public function getAbandonedInquiryList()
    {
        return $this->find()
            ->join('LEFT JOIN', 'inquiry_doctor_list as list', 'list.inquiry_id = inquiry.id')
            ->where(
                ['and',
                    ['not in', 'list.inquiry_id', $this->getFinalizedInquiryListId()],
                    ['not in', 'list.inquiry_id', $this->getPendingInquiryListId()],
                ]
            );
    }

    /**
     * @return int
     */
    public static function getPendingInquiryCount()
    {
        $inquiry = new Inquiry();
        return count($inquiry->getPendingInquiryList()->all());
    }

    /**
     * @return int|string
     */
    public static function getFinalizedInquiryCount()
    {
        return InquiryDoctorList::find()->where(['=', 'status', InquiryDoctorList::STATUS_FINALIZED])->count();
    }

    /**
     * @param $status
     * @return array
     */
    public static function getAnswerStatus($status)
    {
        $array = [
            self::STATUS_PENDING => Yii::t('app','Pending'),
            self::STATUS_COMPLETED => Yii::t('app','Completed'),
            self::STATUS_ABANDONED => Yii::t('app','Voided'),
            self::STATUS_REFUNDED => Yii::t('app','Refunded'),
        ];

        return ($status === false) ? $array : $array[$status];
    }

    /**
     * @param $inquiry
     * @param bool|false $return_string
     * @return array|int
     */
    public function getInquiryStatus($inquiry, $return_string = false)
    {
        $is_expired = $this->checkExpired($inquiry->created_at);

        if(empty($inquiry->finalizedInquiry)) {
            if (!$is_expired) {
                $return = self::STATUS_PENDING;
            } elseif ($is_expired) {
                $return = self::STATUS_ABANDONED;
            }
        } else {
            $return = self::STATUS_COMPLETED;
        }
        return self::getAnswerStatus($return);
    }

    /**
     * @return array
     */
    private function getFinalizedInquiryListId()
    {
        $inquiry_list_array = InquiryDoctorList::find()->where(['=', 'status', InquiryDoctorList::STATUS_FINALIZED])->all();
        $inquiry_id_list = [];
        foreach ($inquiry_list_array as $list_item) {
            $inquiry_id_list[] = $list_item->inquiry_id;
        }
        return $inquiry_id_list;
    }

    /**
     * @return array
     */
    private function getPendingInquiryListId()
    {
        $inquiry_list_array = $this->getPendingInquiryList()->all();
        $inquiry_id_list = [];

        foreach ($inquiry_list_array as $list_item) {
            $inquiry_id_list[] = $list_item->id;
        }
        return $inquiry_id_list;
    }

    public static function countUserActivities()
    {
        if (Yii::$app->user->can('administrator')) {
            $new_inquiries = Inquiry::find()
                ->where(['is_viewed_by_admin' => Inquiry::IS_NOT_VIEWED])
                ->count();
        } else {
            $new_inquiries = Inquiry::find()
                ->where(['inquiry.is_viewed' => Inquiry::IS_NOT_VIEWED])
                ->join('LEFT JOIN', 'inquiry_doctor_list as list', 'list.inquiry_id = inquiry.id')
                ->andWhere(['list.user_id' => Yii::$app->user->id])
                ->andWhere(['!=', 'list.status', InquiryDoctorList::STATUS_FINALIZED])
                ->count();
        }

        return $new_inquiries;
    }

    /**
     * @return int|string
     */
    public static function countNewInquiries()
    {
        $pending_query = Payment::find()
            ->where(['offer_status' => Payment::OFFER_PENDING])
            ->join('LEFT JOIN', 'inquiry as inquiry', 'inquiry.id = payment.inquiry_id');


        if (!Yii::$app->user->can('administrator')) {
            $pending_query
                ->andWhere(['payment.doctor_id' => Yii::$app->user->id])
                ->andWhere(['inquiry.is_viewed' => Inquiry::IS_NOT_VIEWED]);
        } else {
            $pending_query
            ->andWhere(['inquiry.is_viewed_by_admin' => Inquiry::IS_NOT_VIEWED]);
        }
        return $pending_query->count();
    }

    public static function countNewCompletedInquiries()
    {

        $completed_query = Payment::find()
            ->where(['!=', 'offer_status', Payment::OFFER_PENDING])
            ->join('LEFT JOIN', 'inquiry as inquiry', 'inquiry.id = payment.inquiry_id');


        if (!Yii::$app->user->can('administrator')) {
            $completed_query
                ->andWhere(['payment.doctor_id' => Yii::$app->user->id])
                ->andWhere(['inquiry.is_viewed' => Inquiry::IS_NOT_VIEWED]);
        } else {
            $completed_query
                ->andWhere(['inquiry.is_viewed_by_admin' => Inquiry::IS_NOT_VIEWED]);
        }

        return $completed_query->count();
    }

    public static function countNewRefundedInquiries()
    {
        return Payment::find()
            ->where(['offer_status' => Payment::OFFER_REFUNDED])
            ->join('LEFT JOIN', 'inquiry as inquiry', 'inquiry.id = payment.inquiry_id')
            ->andWhere(['inquiry.is_viewed_by_admin' => Inquiry::IS_NOT_VIEWED])->count();
    }

    public function getOfferData($inquiry_id, $doctor_id)
    {

        $inquiryDoctorList = InquiryDoctorList::findAll([
            'inquiry_id' => $inquiry_id,
            'user_id'=>$doctor_id
        ]);

        $id_list = ArrayHelper::map($inquiryDoctorList, 'id', 'id');
        InquiryDoctorList::updateAll(['is_viewed_by_patient' => InquiryDoctorList::VIEWED_STATUS_YES], ['id' => $id_list]);

        $returnData = [];
        $list = $inquiryDoctorList[0];
        /* @var $list InquiryDoctorList */
        if ($list->inquiry->type == Inquiry::TYPE_BRAND) {
            foreach ($list->inquiry->inquiryBrands as $brand) {
                $doctor_offer = InquiryDoctorList::findOne(['user_id' => $doctor_id, 'inquiry_id' => $inquiry_id, 'param_id'=> $brand->brand_param_id]);
                if ($doctor_offer) {
                    if ($brand->brandParam->brand->is_dropdown == 1) {
                        $price = DoctorBrand::findOne(['user_id' => $doctor_id, 'brand_param_id' => $brand->brandParam->brand->brandParams[0]->id])->price;
                        $price = $price * $brand->brandParam->value;
                    } else {
                        $price = $doctor_offer->price;
                    }

                    $returnData[$brand->id] = [
                        'id'=>$doctor_offer->id,
                        'brand'=>$brand->brandParam->brand->name,
                        'price'=> $price,
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
                $doctor_offer = InquiryDoctorList::findOne(['user_id' => $doctor_id, 'inquiry_id' => $inquiry_id, 'param_id'=>$treatment->treatment_param_id]);

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
                        'id' => InquiryDoctorList::findOne(['inquiry_id' => $list->inquiry_id, 'param_id' => $treatment->treatment_param_id, 'user_id' => $doctor_id])->id,
                        'procedure_name' => $procedure_name,
                        'param' => $treatment->treatmentParam->value,
                        'price' => $price,
                        'param_name' => $type,
                        'amount' => $count,
                    ];
                }
            }

        }
        /** @var UserProfile $userProfile */
        $userProfile = UserProfile::findOne($list->user->id);
        $data = [
            'clinic'=> $list->user->doctor->clinic,
            'doctor' => $list->user->userProfile->getFullName(),
            'photo'=> $userProfile->avatar_path ? $userProfile->avatar_base_url.'/'.$userProfile->avatar_path : false,
            'address'=>[
                'zip_code' => $userProfile->zipcode,
                'state_id' => $userProfile->state_id,
                'city' => $userProfile->city,
                'address'=>$userProfile->address
            ],
            'data'=>$returnData
        ];
        return $data;
    }

}
