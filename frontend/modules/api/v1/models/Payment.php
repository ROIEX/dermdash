<?php
namespace frontend\modules\api\v1\models;


use common\components\CardValidator;
use common\components\StripePayment;
use common\models\Inquiry;
use common\models\InquiryDoctorList;
use common\models\PaymentItems;
use common\models\PromoCode;
use common\models\PromoUsed;
use common\models\Settings;
use common\models\UsePromoCode;
use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;

class Payment extends Model
{
    public $inquiry_doctor_id;

    public $stripeToken;

    public $amount;

    public $description;

    public $promo_code;

    public function rules()
    {
        return [
            [['inquiry_doctor_id','stripeToken'],'required'],
            ['amount','integer'],
            [['inquiry_doctor_id'],'checkInquiry'],
            [['description', 'promo_code'], 'string'],
        ];
    }

    /**
     * Validation for inquiry id.
     */
    public function checkInquiry()
    {
        $models = InquiryDoctorList::findAll($this->inquiry_doctor_id);
        if (!empty($models)) {
            foreach ($models as $model) {
                /* @var $model InquiryDoctorList */
                if ($model->inquiry->user_id != Yii::$app->user->id) {
                    $this->addError('inquiry_doctor_id',Yii::t('app','This is not your inquiry doctor.'));
                    // Than check min price with bonuses.
                } elseif ($model->status == $model::STATUS_FINALIZED) {
                    $this->addError('inquiry_doctor_id',Yii::t('app','Already paid.'));
                }
            }
        } else {
            $this->addError('inquiry_doctor_id',Yii::t('app','Inquiry doctor not found.'));
        }
    }

    /**
     * Use this function ONLY if validate is true.
     */
    public function pay()
    {
        $promo_bonus = 0;
        $rewards = Yii::$app->user->identity->getBonusesCount();
        $max_rewards = Settings::findOne(Settings::MAX_REWARD_COUNT_ON_PAYMENT)->value;

        if (!empty($this->promo_code)) {
            /** @var UsePromoCode $promo_code */
            $promo_code = new UsePromoCode();
            $promo_code->promo_code = $this->promo_code;

            if (!$promo_code->validate()) {

                \Yii::$app->response->setStatusCode(422);
                return $promo_code->errors;
            }

            $promo_bonus = $promo_code->useCode()->value;
        }



        $paid_offers = InquiryDoctorList::findAll($this->inquiry_doctor_id);
        $inquiry_offers = InquiryDoctorList::findAll(['inquiry_id' => $paid_offers[0]->inquiry_id]);
        $offer_id_list = ArrayHelper::map($inquiry_offers, 'id', 'id');
        InquiryDoctorList::updateAll(['is_viewed_by_patient' => InquiryDoctorList::VIEWED_STATUS_YES], ['id' => $offer_id_list]);

        $this->description = Yii::t('app','Inquiry Payment.');

        if (!empty($paid_offers)) {
            $this->amount = 0;
            foreach ($paid_offers as $offer) {
                $this->amount += $offer->price;
            }
        }

        if ($rewards != 0) {
            if (($this->amount - $promo_bonus) <= $rewards) {
                $bonuses = $this->amount - $promo_bonus;
                $bonuses = ($bonuses < 0) ? 0 : $bonuses;
            } else {
                $bonuses = $rewards;
            }
            $bonuses = ($bonuses > $max_rewards) ? $max_rewards : $bonuses;
        } else {
            $bonuses = 0;
        }

        $amount = $this->amount - $bonuses - $promo_bonus;
        $amount = ($amount <= 0) ? 0.5 : $amount;

        $payment = new StripePayment(
            $this->stripeToken,
            $amount,
            $this->description
        );

        $pay = $payment->pay($paid_offers[0]->inquiry_id, $paid_offers[0]->user_id);
        $profile = Yii::$app->user->identity->userProfile;
        $profile->reward = $profile->reward - $bonuses;

        if ($profile->reward < 0) {
            $profile->reward = 0;
        }

        $profile->save(false);
        $booleanResult = $pay['status'] == $payment::SUCCEEDED_STATUS;
        if ($booleanResult) {
            $payment_items = new PaymentItems();
            $payment_list = [];
            foreach ($paid_offers as $model) {
                /* @var $model InquiryDoctorList */
                $model->status = $model::STATUS_FINALIZED;
                $model->paid_at = $pay['created'];
                $model->save(false);
                $payment_list[] = [
                    $pay['payment_history_id'],
                    $model->id,
                ];
            }

            $registration_usage = PromoUsed::find()
                ->andWhere(['used_while' => PromoUsed::USED_WHILE_REGISTRATION])
                ->andWhere(['counted' => PromoUsed::NOT_COUNTED])
                ->andWhere(['user_id' => \Yii::$app->user->id])
                ->one();

            if ($registration_usage) {
                $copy_usage = new PromoUsed();
                $copy_usage->promo_id = $registration_usage->promo_id;
                $copy_usage->user_id = $registration_usage->user_id;
                $copy_usage->used_while = PromoUsed::USED_WHILE_PURCHASE;
                $copy_usage->save(false);

                $registration_usage->counted = PromoUsed::COUNTED;
                $registration_usage->update(false);
            }

            $payment_items->saveItems($payment_list);
            $profile->addBonus(Settings::findOne(Settings::REWARD_AFTER_PAYMENT)->value * $this->amount / 100);
        }
        return $booleanResult;
    }

    public function attributeLabels()
    {
        return [
            'stripeToken'=>Yii::t('app','Stripe Token'),
            'amount'=> Yii::t('app','Amount'),
            'description'=>Yii::t('app','Description')
        ];
    }
}