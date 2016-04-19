<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%doctor_answer}}".
 *
 * @property integer $id
 * @property integer $inquiry_doctor_list_id
 * @property string $answer
 *
 * @property InquiryDoctorList $inquiryDoctorList
 */
class DoctorAnswer extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%doctor_answer}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['inquiry_doctor_list_id'], 'integer'],
            [['answer'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'inquiry_doctor_list_id' => Yii::t('app', 'Inquiry Doctor List ID'),
            'answer' => Yii::t('app', 'Answer'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInquiryDoctorList()
    {
        return $this->hasOne(InquiryDoctorList::className(), ['id' => 'inquiry_doctor_list_id']);
    }
}
