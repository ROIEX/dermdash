<?php
/**
 * Created by PhpStorm.
 * User: kharalampidi
 * Date: 19.01.16
 * Time: 10:49
 */

namespace common\models;


use yii\base\Model;

class UsePromoCode extends Model
{
    public $promo_code;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            ['promo_code','required'],
            ['promo_code','checkAvailability'],
        ];
    }

    public function checkAvailability($attribute, $params)
    {
        $model = PromoCode::find()->where(['LIKE BINARY', 'text', $this->$attribute])->one();
        /* @var $model PromoCode */
        if ($model) {
            $check_if_used = PromoUsed::findAll(['promo_id' => $model->id]);
            if (!empty($check_if_used)) {
                if ($model->is_reusable == PromoCode::SINGLE_USE) {
                    $this->addError($attribute,\Yii::t('app', 'This Promo Code can only be used once'));
                } else {
                    foreach ($check_if_used as $used_item) {
                        if ($used_item->user_id == \Yii::$app->user->identity->id) {
                            $this->addError($attribute,\Yii::t('app', 'You already used this promo code'));
                        }
                    }
                }
            }

            $check_invitation_promo = RegistrationInvite::findOne(['promo_id' => $model->id]);
            if ($check_invitation_promo) {
                $this->addError($attribute,\Yii::t('app', 'You can`t use invitation promo code'));
            }

        } else {
            $this->addError($attribute,\Yii::t('app','Wrong promo code'));
        }


    }

    /**
     * @return null|static
     */
    public function getPromoCode()
    {
        return PromoCode::findOne(['text' => $this->promo_code]);
    }

    /**
     * @param mixed $promo_code
     */
    public function setPromoCode($promo_code)
    {
        $this->promo_code = $promo_code;
    }


    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'promo_code'=>\Yii::t('app','Promo Code')
        ];
    }

    /**
     * Using promo code method.
     * @return bool
     */
    public function useCode()
    {
        if (($model = PromoCode::findOne(['text'=>$this->promo_code])) !== null) {
            if (!is_null($model->user_id)) {
                if ($model->user_id != \Yii::$app->user->identity->id) {
                    \Yii::$app->response->setStatusCode(422);
                    $this->addError('promo_code',\Yii::t('app','You can`t use this promo code'));
                }
            }

            if ($model->is_reusable == PromoCode::SINGLE_USE) {
                $check_if_used = PromoUsed::findAll(['promo_id' => $model->id]);
                if (!empty($check_if_used)) {
                    \Yii::$app->response->setStatusCode(422);
                    $this->addError('promo_code',\Yii::t('app','This promo code can be used only once'));
                }
            }

            /* @var $model PromoCode */
            $usedCode = new PromoUsed();
            $usedCode->promo_id = $model->id;
            $usedCode->user_id = \Yii::$app->user->id;
            $usedCode->used_while = PromoUsed::USED_WHILE_PURCHASE;

            if ($usedCode->save()) {
                return $usedCode->getPromoCode()->one();
            }

            /** @var PromoUsed $registration_usage */

            return null;
        }
        \Yii::$app->response->setStatusCode(422);
        $this->addError('promo_code',\Yii::t('app','Incorrect Promo Code'));
        return false;
    }
}