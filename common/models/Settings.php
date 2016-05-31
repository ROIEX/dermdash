<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "settings".
 *
 * @property integer $id
 * @property string $name
 * @property integer $value
 * @property string $description
 */
class Settings extends \yii\db\ActiveRecord
{
    const INQUIRY_DOCTOR_QUANTITY_ID = 1;
    const REGISTRATION_BIDDER_PROMO_VALUE_ID = 2;
    const REGISTRATION_RECEIVER_PROMO_VALUE_ID = 3;
    const REWARD_AFTER_PAYMENT = 4;
    const MAX_REWARD_COUNT_ON_PAYMENT = 5;
    const PAYMENT_FEE = 6;
    const GUEST_INQUIRY_DOCTOR_QUANTITY_ID = 7;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'settings';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'value'], 'required'],
            ['name', 'string', 'max' => 128],
            ['value', 'integer', 'min' => 0],
            [['description'], 'string', 'max' => 256]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'value' => Yii::t('app', 'Value'),
            'description' => Yii::t('app', 'Description'),
        ];
    }

    /**
     * @return int
     */
    public static function getInquiryDoctorQuantity()
    {
        return self::findOne(self::INQUIRY_DOCTOR_QUANTITY_ID)->value;
    }

    public static function getInquiryDoctorQuantityGuest()
    {
        return self::findOne(self::GUEST_INQUIRY_DOCTOR_QUANTITY_ID)->value;
    }
}
