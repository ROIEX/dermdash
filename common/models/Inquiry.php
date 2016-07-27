<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;

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
 * @property InquiryDoctorList[] $BookedInquiry
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
    const STATUS_BOOKED = 4;

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
            'created_at' => Yii::t('app', 'Created at'),
            'comment' => Yii::t('app', 'Comment'),
        ];
    }

    public function afterFind()
    {
        if (Yii::$app->user->can('administrator') && $this->is_viewed_by_admin == self::IS_NOT_VIEWED) {
            $this->is_viewed_by_admin = self::IS_VIEWED;
            $this->update(false);
        } elseif (Yii::$app->user->can('manager') && !Yii::$app->user->can('administrator') && $this->is_viewed == self::IS_NOT_VIEWED) {
            $this->is_viewed = self::IS_VIEWED;
            $this->update(false);
        }
        parent::afterFind();
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
     * @return $this
     */
    public function getBookedInquiry()
    {
        return $this->hasMany(InquiryDoctorList::className(), ['inquiry_id' => 'id'])->where([InquiryDoctorList::tableName() . '.' .'status' => InquiryDoctorList::STATUS_BOOKED]);
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
     * @return mixed
     */
    public function getBookedDoctor()
    {
        return InquiryDoctorList::find()->where(['=', 'status', InquiryDoctorList::STATUS_BOOKED])->one()->user->doctor;
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
        $inquiry_doctor_list = InquiryDoctorList::find()
            ->select('inquiry_id')
            ->where(['>=', 'created_at', time() - 3600 * 24 * self::INQUIRY_DAYS_ACTIVE])
            ->andWhere(['!=', 'status', InquiryDoctorList::STATUS_FINALIZED])
            ->groupBy('inquiry_id');

        if (!Yii::$app->user->can('administrator')) {
            $inquiry_doctor_list->andWhere(['user_id' => Yii::$app->user->id]);
        }
        $inquiry_doctor_list = $inquiry_doctor_list->all();
        $ids = ArrayHelper::getColumn($inquiry_doctor_list, 'inquiry_id');
        return $this->find()->where(['in', 'id', $ids])->orderBy(['created_at' => SORT_DESC]);
    }


    public function getAbandonedInquiryList()
    {
        $inquiry_doctor_list = InquiryDoctorList::find()
            ->select('inquiry_id')
            ->where(['<', 'created_at', time() - 3600 * 24 * self::INQUIRY_DAYS_ACTIVE])
            ->andWhere(['!=', 'status', InquiryDoctorList::STATUS_FINALIZED])
            ->groupBy('inquiry_id');


        if (!Yii::$app->user->can('administrator')) {
            $inquiry_doctor_list->andWhere(['user_id' => Yii::$app->user->id]);
        }
        $inquiry_doctor_list = $inquiry_doctor_list->all();
        $ids = ArrayHelper::getColumn($inquiry_doctor_list, 'inquiry_id');
        return $this->find()->where(['in', 'id', $ids])->orderBy(['created_at' => SORT_DESC]);
    }

    /**
     * @return int
     */
    public static function getPendingInquiryCount()
    {
        $inquiry = new Inquiry();
        return $inquiry->getPendingInquiryList()->count();
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
            self::STATUS_PENDING => Yii::t('app','Not purchased'),
            self::STATUS_COMPLETED => Yii::t('app','Purchased'),
            self::STATUS_ABANDONED => Yii::t('app','Voided'),
            self::STATUS_REFUNDED => Yii::t('app','Refunded'),
            self::STATUS_BOOKED => Yii::t('app','Booked'),
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

        if ($return_string) {
            return self::getAnswerStatus($return);
        } else {
            return $return;
        }
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

    public function getOfferData($note_id, $user_id = false)
    {
        $inquiryDoctorList = InquiryDoctorList::find()
            ->where(['inquiry_id' => (int)$note_id])
            ->with('inquiry')
            ->with('treatmentParam.treatment')
            ->with('user.userProfile');
        if ($user_id) {
            $inquiryDoctorList->andWhere(['inquiry_doctor_list.user_id' => $user_id]);
        }

        $inquiryDoctorList = $inquiryDoctorList->all();

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
                    'status' => InquiryDoctorList::getAnswerStatus($doctor_offer->status)
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
                        $count += $intensity->count * (isset($doctor_offer->treatmentParam->treatment->treatmentSessions->session_count) ? $doctor_offer->treatmentParam->treatment->treatmentSessions->session_count : 1)    ;
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

                $returnData = [
                    'id' => $doctor_offer->id,
                    'procedure_name' => $procedure_name,
                    'param' => $doctor_offer->treatmentParam->value,
                    'price' => $price,
                    'param_name' => $type,
                    'amount' => $count,
                    'status' => InquiryDoctorList::getAnswerStatus($doctor_offer->status)
                ];

                if (!isset($data[$doctor_offer->user_id])) {
                    $data[$doctor_offer->user_id] = [
                        'clinic'=> $doctor_offer->user->doctor->clinic,
                        'doctor' => $doctor_offer->user->userProfile->getFullName(),
                        'data' => []
                    ];
                }

                array_push($data[$doctor_offer->user_id]['data'], $returnData);
            }
        } else {
            return false;
        }
        return $data;
    }
}
