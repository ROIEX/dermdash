<?php
/**
 * Created by PhpStorm.
 * User: rexit
 * Date: 4/15/2016
 * Time: 5:30 PM
 */

namespace common\components;


use common\models\Inquiry;
use common\models\InquiryDoctorList;
use common\models\Payment;
use common\models\Settings;
use yii\helpers\VarDumper;

class SummaryInvoice
{
    public $total_price;
    public $fee;
    public $net_total;
    public $company_name;
    public $address;
    public $location;
    public $phone;
    public $invoice_date;
    public $invoice_number;
    public $items;

    public function __construct($payments)
    {
        $this->fee = Settings::findOne(Settings::PAYMENT_FEE)->value;
        $this->company_name = 'DermDash';
        $this->address = \Yii::$app->params['company']['address'];
        $this->location = \Yii::$app->params['company']['location'];
        $this->phone = \Yii::$app->params['company']['phone'];
        $this->invoice_items = $this->doctorSummaryInvoice($payments);

    }

    private function doctorSummaryInvoice($payments)
    {
        $invoice_array = [];
        $doctor_id_list = [];
        /** @var Payment $payment */
        foreach ($payments as $payment) {
            $doctor_id_list[] = $payment->doctor_id;
        }
        $doctor_id_list = array_unique($doctor_id_list);
        $this->net_total = 0;
        foreach ($doctor_id_list as $doctor_id) {
            $items = [];
            foreach ($payments as $payment) {
                if ($payment->doctor_id == $doctor_id) {
                    $items[] = $this->getDescriptionArray($payment);
                }
            }

            $invoice_array[$doctor_id] = [
                'fee' => $this->fee,
                'company_name' => $this->company_name,
                'address' => $this->address,
                'location' => $this->location,
                'phone' => $this->phone,
                'net_total' => $this->phone,
                'items' => $items
            ];
        }

        $this->net_total *= (1 - $this->fee / 100);
        return $invoice_array;
    }

    private function getDescriptionArray($payment)
    {
        $offer_list = InquiryDoctorList::find()
            ->where(['inquiry_id' => $payment->inquiry_id])
            ->andWhere(['status' => InquiryDoctorList::STATUS_FINALIZED])
            ->all();

        $inquiry = Inquiry::findOne($payment->inquiry_id);

        /** @var InquiryDoctorList $offer */
        foreach ($offer_list as $offer ) {
            $this->net_total += $offer->price;
        }

        if ($inquiry->inquiryTreatments) {
            foreach ($offer_list as $inquiry_item) {

                /** @var InquiryDoctorList $inquiry_item */
                $desc_treatment_name = $inquiry_item->treatmentParam->treatment->name;
                $treatment = [
                    'invoice_number' => $payment->inquiry_id,
                    'param' => $desc_treatment_name . ', ' . $inquiry_item->treatmentParam->value,
                    'price' => $inquiry_item->price,
                    'purchase_date' => $payment->created_at,
                ];

                $inquiry_treatment = $inquiry_item->inquiryTreatment;

                if ($inquiry_treatment->severity_id) {

                    $treatment_severity = $inquiry_treatment->getTreatmentSeveritiesByParam();
                    foreach ($treatment_severity as $severity) {
                        $treatment['used_brands'] = $severity->brandParam->brand->name . ', '. $severity->count * $inquiry_treatment->session->session_count;
                    }

                } elseif ($inquiry_treatment->treatment_intensity_id) {

                    $treatment_intensity = $inquiry_treatment->treatmentIntensity;
                    $treatment['used_brands'] = $treatment_intensity->brandParam->brand->name . ', '. $treatment_intensity->count * $inquiry_treatment->session->session_count;
                }
            }

           return $treatment;

        } elseif ($inquiry->inquiryBrands) {
            /** @var InquiryDoctorList $inquiry_item */
            foreach ($offer_list as $inquiry_item) {

                $brand = [
                    'param' => $inquiry_item->brandParam->brand->name,
                    'used_brands' => $inquiry_item->brandParam->value,
                    'price' => $inquiry_item->price,
                    'purchase_date' => $payment->created_at
                ];
            }

            return $brand;

        } else {
            return false;
        }
    }


}