<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

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
        return $this->hasOne(InquiryDoctorList::className(), ['inquiry_id' => 'id'])->where(['user_id' => Yii::$app->user->id]);
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
}
