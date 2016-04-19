<?php
/**
 * Created by PhpStorm.
 * User: kharalampidi
 * Date: 19.01.16
 * Time: 12:10
 */

namespace common\components;


use common\models\Bonus;
use common\models\Payment;
use Exception;
use Stripe\Charge;
use Stripe\Error\ApiConnection;
use Stripe\Error\Authentication;
use Stripe\Error\Base;
use Stripe\Error\Card;
use Stripe\Error\InvalidRequest;
use Stripe\Error\RateLimit;
use Stripe\Stripe;
use Yii;
use yii\web\BadRequestHttpException;

class StripePayment
{
    public $stripeToken;

    public $amount;

    public $description;

    const SUCCEEDED_STATUS = 'succeeded';
    const MAX_BONUSES_PER_PAYMENT = 20;
    const CENTS = 100;


    /**
     * StripePayment constructor.
     * @param $stripeToken
     * @param $amount
     * @param string $description
     */
    public function __construct($stripeToken, $amount, $description = '')
    {
        $this->stripeToken = $stripeToken;
        $this->description = $description;
        $this->amount = $amount;
    }


    /**
     * @param mixed $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    /**
     * @return string|Charge
     * @throws BadRequestHttpException
     */
    public function pay($inquiry_id, $doctor_id)
    {
        try {
            Stripe::setApiKey(Yii::$app->params['stripe']['secretKey']);

            $amountWithBonuses = $this->amount * self::CENTS;
            $charge = Charge::create([
                'source' => $this->stripeToken,
                'amount' => $amountWithBonuses,
                'currency' => Yii::$app->params['stripe']['currency'],
                'description' => $this->description
            ]);
            $result = $charge->getLastResponse()->json;

            $saveLogs = new Payment();
            $saveLogs->created_at = $result['created'];
            $saveLogs->amount = $amountWithBonuses;
            $saveLogs->payment_id = $result['id'];
            $saveLogs->status = $result['status'];
            $saveLogs->paid = $result['paid'];
            $saveLogs->user_id = Yii::$app->user->id;
            $saveLogs->doctor_id = $doctor_id;
            $saveLogs->inquiry_id = $inquiry_id;
            $saveLogs->offer_status = Payment::OFFER_PENDING;
            $saveLogs->invoice_status = Payment::INVOICE_NOT_SENT;
            $saveLogs->save(false);
            $saveLogs->refresh();
            $result['payment_history_id'] = $saveLogs->id;
            return $result;
        } catch(Card $e) {
            throw new BadRequestHttpException($e->getMessage());
        } catch (RateLimit $e) {
            throw new BadRequestHttpException($e->getMessage());
        } catch (InvalidRequest $e) {
            throw new BadRequestHttpException($e->getMessage());
        } catch (Authentication $e) {
            throw new BadRequestHttpException($e->getMessage());
        } catch (ApiConnection $e) {
            throw new BadRequestHttpException($e->getMessage());
        } catch (Base $e) {
            throw new BadRequestHttpException($e->getMessage());
        } catch (Exception $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
    }

}