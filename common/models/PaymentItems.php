<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "payment_items".
 *
 * @property integer $id
 * @property integer $payment_history_id
 * @property integer $inquiry_doctor_list_id
 */
class PaymentItems extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'payment_items';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['payment_history_id', 'inquiry_doctor_list_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'payment_history_id' => Yii::t('app', 'Payment History ID'),
            'inquiry_doctor_list_id' => Yii::t('app', 'Inquiry Doctor List ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDoctorList()
    {
        return $this->hasOne(InquiryDoctorList::className(), ['id' => 'inquiry_doctor_list_id']);
    }

    /**
     * @param $items
     * @return int
     * @throws \yii\db\Exception
     */
    public function saveItems($items)
    {
        return Yii::$app->db->createCommand()->batchInsert(self::tableName(), $this->activeAttributes(), $items)->execute();
    }
}
