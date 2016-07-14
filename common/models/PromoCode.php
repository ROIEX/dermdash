<?php

namespace common\models;

use common\commands\command\SendEmailCommand;
//use frontend\modules\api\v1\resources\UserProfile;
use Yii;
use yii\web\BadRequestHttpException;

/**
 * This is the model class for table "promo_code".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $text
 * @property string $value
 * @property integer $is_reusable
 * @property string $description
 *
 * @property RegistrationInvite $registrationInvite
 */
class PromoCode extends \yii\db\ActiveRecord
{
    const SINGLE_USE = 0;
    const REUSABLE = 1;
    const PROMO_LENGTH = 6;
    const USER_TYPE_BIDDER = 1;
    const USER_TYPE_RECEIVER = 2;
    const REWARD_FROM_REGISTRATION = 20;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'promo_code';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'is_reusable'], 'integer'],
            ['text', 'string', 'max' => 16],
            ['text', 'unique'],
            ['value', 'integer'],
            [['description'], 'string', 'max' => 128],
            [['text', 'value'], 'required']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'text' => Yii::t('app', 'Code'),
            'value' => Yii::t('app', 'Amount, $'),
            'is_reusable' => Yii::t('app', 'Is reusable'),
            'description' => Yii::t('app', 'Description'),
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function getUsedCountPurchase()
    {
        return $this->hasMany(PromoUsed::className(), ['promo_id' => 'id'])->where(['used_while' => PromoUsed::USED_WHILE_PURCHASE])->count();
    }

    public function getUsedCountRegistration()
    {
        return $this->hasMany(PromoUsed::className(), ['promo_id' => 'id'])->where(['used_while' => PromoUsed::USED_WHILE_REGISTRATION])->count();
    }

    public static function getType($type)
    {
        $array = [
            self::REUSABLE => Yii::t('app', 'Is reusable'),
            self::SINGLE_USE => Yii::t('app', 'Single use'),
        ];
        return $array[$type];
    }

    /**
     * @param $user_type
     * @return bool|int
     * Incoming param: user_type must be in array of self::USER_TYPE_BIDDER, self::USER_TYPE_RECEIVER
     */
    private function getRewardValue($user_type)
    {
        if ($user_type == self::USER_TYPE_BIDDER) {
            $promo_value = Settings::findOne(['id' => Settings::REGISTRATION_BIDDER_PROMO_VALUE_ID]);
        } elseif ($user_type == self::USER_TYPE_RECEIVER) {
            $promo_value = Settings::findOne(['id' => Settings::REGISTRATION_RECEIVER_PROMO_VALUE_ID]);
        } else {
            return false;
        }
        return $promo_value->value;
    }

    /**
     * @param $requester_id
     * @return string
     */
    public function generateRegistrationPromo($requester_id, $email = false)
    {
        $promo = $this->generatePromo($this->getRewardValue(self::USER_TYPE_RECEIVER), $email);
        $this->saveInvitationPromo($requester_id, $promo->id);
        return $promo;
    }

    /**
     * @param $promo_text
     * @return bool
     */
    public function generateAfterRegistrationPromo($promo_text)
    {
        $bidder_id = $this->searchInvitationPromo($promo_text);
        if ($bidder_id) {
            $bidder_profile = \common\models\UserProfile::findOne(['user_id' => $bidder_id]);
            $bidder_profile->reward += $this->getRewardValue(self::USER_TYPE_BIDDER);
            $bidder_profile->update(false);
            return $bidder_profile->update(false);
           // $promo = $this->generatePromo($this->getRewardValue(self::USER_TYPE_BIDDER), $bidder_id);

//            if ($promo) {
//                $user = $promo->getUser()->one();
//                /* @var $user User */
//                Yii::$app->commandBus->handle(new SendEmailCommand([
//                    'from' => [Yii::$app->params['adminEmail'] => Yii::$app->name],
//                    'to' => $user->email,
//                    'subject' => Yii::t('frontend', 'Invite to {app_name}',['app_name'=>Yii::$app->name]),
//                    'view' => 'promoCode',
//                    'params' => [
//                        'model' => $promo,
//                        'mailing_address' => getenv('ADMIN_EMAIL'),
//                        'current_year' => date('Y'),
//                        'app_name' => Yii::$app->name,
//                        ]
//                ]));
//            }
//            return $promo;
        } else {
            return false;
        }
    }

    /**
     * @param $value
     * @param null $user_id
     * @return bool|PromoCode
     */
    private function generatePromo($value, $email = null)
    {
        $promo = new self;
        $promo->text = Yii::$app->security->generateRandomString(self::PROMO_LENGTH);
        $promo->value = $value;
        if (!is_null($email)) {
            $promo->is_reusable = self::SINGLE_USE;
        } else {
            $promo->is_reusable = self::REUSABLE;
        }
        $promo->validate();
        if ($promo->save()) {
            return $promo;
        }
        return false;
    }

    /**
     * @param $bidder_id
     * @param $promo_id
     * Saves generated promo when patient invites other patient for later use on receiver registration
     * @return bool
     */
    private function saveInvitationPromo($bidder_id, $promo_id)
    {
        $promo_save = new RegistrationInvite();
        $promo_save->bidder_id = $bidder_id;
        $promo_save->promo_id = $promo_id;
        $promo_save->status = RegistrationInvite::IS_PENDING;
        return $promo_save->save();
    }

    /**
     * @param $promo_text
     * @return bool
     * Search if there is a registration promo with active status. if there is one, returns id of the user who created it
     */
    public function searchInvitationPromo($promo_text)
    {
        $used_promo = PromoCode::findOne(['text' => $promo_text]);
        if (!empty($used_promo)) {
            $saved_invite_promo = RegistrationInvite::find()->where(['promo_id' => $used_promo->id])->andWhere(['status' => RegistrationInvite::IS_PENDING])->one();
            if (!empty($saved_invite_promo)) {
                /* @var $saved_invite_promo RegistrationInvite */
                $saved_invite_promo->status = RegistrationInvite::IS_COMPLETED;
                $saved_invite_promo->update();
                return $saved_invite_promo->bidder_id;
            } else {
                return false;
            }
        }
        return false;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegistrationInvite()
    {
        return $this->hasOne(RegistrationInvite::className(), ['promo_id'=>'id']);
    }

    /**
     * @param $model_list
     * @param null $discount_size
     * @return array
     * @throws BadRequestHttpException
     */
     public function getDiscountWithPromoCode($model_list, $discount_size = null)
     {
         if (is_null($discount_size)) {
             $discount_size = 0;
         }
         if (!empty($model_list)) {
             $order_price = 0;
             $rewards_amount_earned = 0;
             $max_usable_reward = Settings::findOne(Settings::MAX_REWARD_COUNT_ON_PAYMENT)->value;
             $bonuses_count = Yii::$app->user->identity->getBonusesCount();

             if ($bonuses_count > $max_usable_reward) {
                 $rewards_used = $max_usable_reward;
             } else {
                 $rewards_used = $bonuses_count;
             }

             $summary_discount = $rewards_used + $discount_size;

             foreach ($model_list as $model) {
                 $order_price += (!is_null($model->special_price) && !empty($model->special_price)) ? $model->special_price : $model->price;
             }

             if ($order_price > 0.5) {
                 $rewards_amount_earned = $order_price* (Settings::findOne(Settings::REWARD_AFTER_PAYMENT)->value / 100);
             }

             $final_price = $order_price - $summary_discount;
             if ($final_price <= 0) {
                 $final_price = 0.5;
             }

             $result = [
                 'rewards_amount_used' => $rewards_used,
                 'rewards_amount_earned' => $rewards_amount_earned,
                 'discount_amount_used' => $discount_size,
                 'summary_discount' => $summary_discount,
                 'final_price' => $final_price
             ];
             return $result;
         } else {
             throw new BadRequestHttpException;
         }
     }
}
