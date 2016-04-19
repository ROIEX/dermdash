<?php

namespace common\models;

use common\behaviors\SaveDoctorsBehavior;
use Yii;

/**
 * This is the model class for table "{{%inquiry_brand}}".
 *
 * @property integer $id
 * @property integer $inquiry_id
 * @property integer $brand_param_id
 *
 * @property Inquiry $inquiry
 * @property BrandParam $brandParam
 * @property InquiryDoctorList $doctorList
 */
class InquiryBrand extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%inquiry_brand}}';
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            [
                'class'=>SaveDoctorsBehavior::className()
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['inquiry_id', 'brand_param_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'inquiry_id' => Yii::t('app', 'Inquiry ID'),
            'brand_param_id' => Yii::t('app', 'Brand Param ID'),
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
    public function getBrandParam()
    {
        return $this->hasOne(BrandParam::className(), ['id' => 'brand_param_id']);
    }

    /**
     * @return $this
     */
    public function getDoctorList()
    {
        return $this->hasMany(InquiryDoctorList::className(), ['inquiry_id' => 'inquiry_id'])->where(['inquiry_doctor_list.param_id' => $this->brand_param_id]);
    }
}
