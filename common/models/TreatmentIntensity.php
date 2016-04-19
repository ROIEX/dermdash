<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "treatment_intensity".
 *
 * @property integer $id
 * @property integer $treatment_id
 * @property integer $intensity_id
 * @property integer $brand_param_id
 * @property integer $count
 * @property integer $status
 *
 * @property BrandParam $brandParam
 * @property Intensity $intensity
 */
class TreatmentIntensity extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'treatment_intensity';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['treatment_id', 'intensity_id', 'brand_param_id', 'count', 'status'], 'integer'],
            [['intensity_id', 'brand_param_id', 'count', 'status'], 'required', 'when' => function() {
                return false;
            }, 'whenClient' => "function(attribute, value) {
                      return $('#treatment-intensity').is(':checked');
                  }"],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'treatment_id' => Yii::t('app', 'Treatment ID'),
            'intensity_id' => Yii::t('app', 'Intensity'),
            'brand_param_id' => Yii::t('app', 'Brand'),
            'count' => Yii::t('app', 'Count'),
            'status' => Yii::t('app', 'Status (Active/Not active)'),
        ];
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBrandParam()
    {
        return $this->hasOne(BrandParam::className(), ['id' => 'brand_param_id']);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIntensity()
    {
        return $this->hasOne(Intensity::className(), ['id' => 'intensity_id']);
    }
}
