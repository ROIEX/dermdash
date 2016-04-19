<?php

namespace frontend\modules\api\v1\models;


use common\models\InquiryDoctorList;
use Yii;
use yii\base\Model;

class DiscountWithPromo extends Model
{
    public $inquiry_doctor_id;

    public $discount_size;

    public function rules()
    {
        return [
            ['inquiry_doctor_id', 'required'],
            ['inquiry_doctor_id', function(){
                if (!is_array($this->inquiry_doctor_id)) {
                    return $this->addError('inquiry_doctor_id', Yii::t('app','Must be an array.'));
                }
                foreach ($this->inquiry_doctor_id as $inquiry_id) {
                    $model = InquiryDoctorList::findOne($inquiry_id);
                    if ($model !== null) {
                        /* @var $model InquiryDoctorList */
                        if ($model->inquiry->user_id != Yii::$app->user->id) {
                            return $this->addError('inquiry_doctor_id', Yii::t('app','This is not your inquiry doctor.'));
                            // Than check min price with bonuses.
                        }
                    } else {
                        return $this->addError('inquiry_doctor_id', Yii::t('app','Inquiry doctor not found.'));
                    }
                }
            }],
            ['discount_size', 'number']
        ];
    }

    public function attributeLabels()
    {
        return [
            'inquiry_doctor_id'=>\Yii::t('app','Inquiry Doctor List Id'),
            'discount_size'=>\Yii::t('app','Discount size')
        ];
    }

    /**
     * @return mixed
     */
    public function getDiscount()
    {
        if ($this->validate()) {
            $model_list = InquiryDoctorList::find()->where(['in', 'id', $this->inquiry_doctor_id])->all();
            /* @var $model InquiryDoctorList */
            return (new PromoCode())->getDiscountWithPromoCode($model_list, $this->discount_size);

        }
        return $this->errors;
    }

}