<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%inquiry_doctor_list}}".
 *
 * @property integer $id
 * @property integer $inquiry_id
 * @property integer $user_id
 * @property string  $price
 * @property double  $special_price
 * @property integer  $status
 * @property integer  $created_at
 * @property integer  $paid_at
 * @property integer  $param_id
 * @property integer  $is_viewed_by_patient
 * @property integer  $is_viewed
 *
 * @property Inquiry $inquiry
 * @property User $user
 * @property PaymentItem $paymentItem
 * @property TreatmentParam $treatmentParam
 * @property InquiryTreatment $inquiryTreatment
 * @property BrandParam $brandParam
 * @property mixed $param
 */
class InquiryDoctorList extends \yii\db\ActiveRecord
{
    const STATUS_NOT_ANSWERED = 0;
    const STATUS_ANSWER_YES = 1;
    const STATUS_FINALIZED = 3;
    const STATUS_BOOKED = 4;

    const VIEWED_STATUS_YES = 1;
    const VIEWED_STATUS_NO = 0;


    const SCENARIO_CREATE_OFFER = 'offer';

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%inquiry_doctor_list}}';
    }

    /**
     *
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['inquiry_id', 'user_id', 'status','paid_at','created_at', 'param_id'], 'integer'],
            [['price'], 'string', 'max' => 16],
            ['special_price', 'double'],
            [['price'], 'required', 'on' => self::SCENARIO_CREATE_OFFER],
            [['is_viewed', 'is_viewed_by_patient'], 'default', 'value' => self::VIEWED_STATUS_NO]
        ];
    }

    public function scenarios()
	{
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_CREATE_OFFER] = ['price'];
        return $scenarios;

	 }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'inquiry_id' => Yii::t('app', 'Inquiry ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'price' => Yii::t('app', 'Price'),
            'special_price' => Yii::t('app', 'Special Price'),
            'status' => Yii::t('app', 'Status')
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInquiry()
    {
        return $this->hasOne(Inquiry::className(), ['id' => 'inquiry_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInquiryTreatment()
    {
        return $this->hasOne(InquiryTreatment::className(), ['inquiry_id' => 'inquiry_id'])->where(['inquiry_treatment.treatment_param_id' => $this->param_id]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTreatmentParam()
    {
        return $this->hasOne(TreatmentParam::className(), ['id' => 'param_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBrandParam()
    {
        return $this->hasOne(BrandParam::className(), ['id' => 'param_id']);
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
    public function gePaymentItem()
    {
        return $this->hasOne(PaymentItems::className(), ['inquiry_doctor_list_id' => 'id']);
    }

    /**
     * @param $className
     * @return \yii\db\ActiveQuery
     */
    public function getParam($className)
    {
        return $this->hasOne($className,['id'=>'param_id']);
    }

    public static function getAnswerStatus($status)
    {
        $array = [
            self::STATUS_ANSWER_YES => Yii::t('app','Answered yes'),
            self::STATUS_FINALIZED => Yii::t('app','Completed'),
        ];
        return $status ? $array[$status] : Yii::t('app', 'Not provided');
    }

    public function beforeSave($insert)
    {
        if ($this->isNewRecord) {
            $this->status = self::STATUS_ANSWER_YES;
        }
        return parent::beforeSave($insert);
    }
}