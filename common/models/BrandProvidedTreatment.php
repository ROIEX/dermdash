<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "brand_provided_treatment".
 *
 * @property integer $id
 * @property integer $treatment_param_id
 * @property integer $brand_param_id
 * @property integer $count
 *
 * @property BrandParam $brandParam
 */
class BrandProvidedTreatment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'brand_provided_treatment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['treatment_param_id', 'brand_param_id'], 'integer'],
            [['brand_param_id', 'count'], 'required'],
            ['count', 'double']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'treatment_param_id' => Yii::t('app', 'Treatment Param ID'),
            'brand_param_id' => Yii::t('app', 'Brand Param'),
            'count' => Yii::t('app', 'Count'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBrandParam()
    {
        return $this->hasOne(BrandParam::className(),['id'=>'brand_param_id']);
    }
}
