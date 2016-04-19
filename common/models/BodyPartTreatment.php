<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%body_part_treatment}}".
 *
 * @property integer $id
 * @property integer $treatment_id
 * @property integer $body_part_id
 *
 * @property BodyPart $bodyPart
 * @property Treatment $treatment
 * @property InquiryTreatment[] $inquiryTreatments
 */
class BodyPartTreatment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%body_part_treatment}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['treatment_id', 'body_part_id'], 'integer']
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
            'body_part_id' => Yii::t('app', 'Body Part ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBodyPart()
    {
        return $this->hasOne(BodyPart::className(), ['id' => 'body_part_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTreatment()
    {
        return $this->hasOne(Treatment::className(), ['id' => 'treatment_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInquiryTreatments()
    {
        return $this->hasMany(InquiryTreatment::className(), ['body_part_treatment_id' => 'id']);
    }
}
