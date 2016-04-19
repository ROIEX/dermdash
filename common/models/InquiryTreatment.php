<?php

namespace common\models;

use common\behaviors\SaveDoctorsBehavior;
use Yii;

/**
 * This is the model class for table "{{%inquiry_treatment}}".
 *
 * @property integer $id
 * @property integer $inquiry_id
 * @property integer $treatment_param_id
 * @property integer $session_id
 * @property integer $additional_attribute_id
 * @property integer $severity_id
 * @property integer $treatment_intensity_id
 *
 * @property AdditionalAttributeItem $additionalAttribute
 * @property Inquiry $inquiry
 * @property TreatmentParam $treatmentParam
 * @property TreatmentSession $session
 * @property Severity $severity
 * @property TreatmentIntensity $treatmentIntensity
 * @property InquiryDoctorList $doctorList
 */
class InquiryTreatment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%inquiry_treatment}}';
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            [
                'class' => SaveDoctorsBehavior::className()
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['inquiry_id', 'treatment_param_id', 'session_id', 'additional_attribute_id', 'severity_id', 'treatment_intensity_id'], 'integer']
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
            'treatment_param_id' => Yii::t('app', 'Treatment Param ID'),
            'session_id' => Yii::t('app', 'Session ID'),
            'additional_attribute_id' => Yii::t('app', 'Additional Attribute ID'),
            'severity_id' => Yii::t('app', 'Severity Param ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdditionalAttribute()
    {
        return $this->hasOne(AdditionalAttributeItem::className(), ['id' => 'additional_attribute_id']);
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
    public function getTreatmentParam()
    {
        return $this->hasOne(TreatmentParam::className(), ['id' => 'treatment_param_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSession()
    {
        return $this->hasOne(TreatmentSession::className(), ['id' => 'session_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSeverity()
    {
        return $this->hasOne(Severity::className(), ['id' => 'severity_id']);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTreatmentIntensity()
    {
        return $this->hasOne(TreatmentIntensity::className(), ['id' => 'treatment_intensity_id']);
    }

    /**
     * @return $this
     */
    public function getDoctorList()
    {
        return $this->hasMany(InquiryDoctorList::className(), ['inquiry_id' => 'inquiry_id'])->where(['inquiry_doctor_list.param_id' => $this->treatment_param_id]);
    }

    /**
     * @return TreatmentParamSeverity[]
     */
    public function getTreatmentSeveritiesByParam()
    {
        return TreatmentParamSeverity::find()->where(['param_id' => $this->treatment_param_id])->andWhere(['severity_id' => $this->severity_id])->all();
    }
}