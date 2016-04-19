<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "treatment_intensity_discounts".
 *
 * @property integer $id
 * @property integer $treatment_id
 * @property integer $session_id
 * @property double $discount_value
 */
class TreatmentIntensityDiscounts extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'treatment_intensity_discounts';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['treatment_id', 'session_id'], 'integer'],
            [['discount_value'], 'number']
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
            'session_id' => Yii::t('app', 'Session ID'),
            'discount_value' => Yii::t('app', 'Discount Value'),
        ];
    }
}
