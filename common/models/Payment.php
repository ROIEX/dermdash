<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%payment}}".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $payment_id
 * @property integer $created_at
 * @property integer $paid
 * @property string $status
 * @property integer $amount
 * @property integer $inquiry_id
 * @property integer $doctor_id
 * @property integer $offer_status
 * @property integer $invoice_status
 *
 * @property User $user
 * @property User $doctor
 * @property PaymentItems $paymentItems
 */
class Payment extends \yii\db\ActiveRecord
{
    const INVOICE_NOT_SENT = 0;
    const INVOICE_SENT = 1;

    const OFFER_PENDING = 0;
    const OFFER_COMPLETED = 1;
    const OFFER_REFUND_REQUESTED = 2;
    const OFFER_REFUNDED = 3;

    const STATUS_SUCCEEDED = 'succeeded';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%payment}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'doctor_id', 'inquiry_id', 'created_at', 'paid', 'amount', 'invoice_status', 'offer_status'], 'integer'],
            [['payment_id', 'status'], 'string', 'max' => 255],
            ['invoice_status', 'default', 'value' => self::INVOICE_NOT_SENT],
            ['offer_status', 'default', 'value' => self::OFFER_PENDING],
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
            'payment_id' => Yii::t('app', 'Payment ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'paid' => Yii::t('app', 'Paid'),
            'status' => Yii::t('app', 'Status'),
            'amount' => Yii::t('app', 'Amount'),
            'invoice_status' => Yii::t('app', 'Invoice status'),
            'offer_status' => Yii::t('app', 'Offer status'),
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
    public function getDoctor()
    {
        return $this->hasOne(User::className(), ['id' => 'doctor_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPaymentItems()
    {
        return $this->hasMany(PaymentItems::className(), ['payment_history_id' => 'id']);
    }

    /**
     * @return array
     */
    public static function getAdminOfferStatusArray()
    {
        $array = [
            self::OFFER_PENDING => Yii::t('app', "Pending"),
            self::OFFER_COMPLETED => Yii::t('app', "Completed"),
            self::OFFER_REFUND_REQUESTED => Yii::t('app', "Refund Requested"),
            self::OFFER_REFUNDED => Yii::t('app', "Refunded"),
        ];
        return $array;
    }

    public static function getDoctorOfferStatusArray()
    {
        $array = [
            self::OFFER_PENDING => Yii::t('app', "Pending"),
            self::OFFER_COMPLETED => Yii::t('app', "Completed"),
            self::OFFER_REFUND_REQUESTED => Yii::t('app', "Refund Request"),
            self::OFFER_REFUNDED => Yii::t('app', "Refunded"),
        ];
        return $array;
    }

    /**
     * @param $status
     * @return mixed
     */
    public static function getOfferStatus($status)
    {
        $array = self::getAdminOfferStatusArray();
        return $array[$status];
    }

}