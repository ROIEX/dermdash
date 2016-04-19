<?php

namespace common\components;

use common\models\Inquiry;
use common\models\InquiryDoctorList;
use common\models\Payment;
use common\models\Settings;

class Invoice
{
    public $purchase_date;
    public $item_description;
    public $total_price;
    public $fee;
    public $net_total;
    public $company_name;
    public $address;
    public $location;
    public $phone;
    public $invoice_date;
    public $invoice_number;
    public $treatment_array;
    public $brand_array;

    public function __construct(Payment $payment, $for_pdf = false)
    {
        $this->purchase_date = $payment->created_at;
        $this->total_price = $payment->amount;
        $this->fee = Settings::findOne(Settings::PAYMENT_FEE)->value;
        $this->net_total = $this->total_price * (1 - $this->fee/100);
        //$this->item_description = $this->getDescription($inquiry);
        $this->company_name = 'DermDash';
        $this->address = \Yii::$app->params['company']['address'];
        $this->location = \Yii::$app->params['company']['location'];
        $this->phone = \Yii::$app->params['company']['phone'];

        if ($for_pdf) {
            $this->item_array = $this->getDescriptionArray($payment);
        }
    }

    public function getDescription(Inquiry $inquiry)
    {
        $description_params = [];
        if ($inquiry->inquiryTreatments) {
            foreach ($inquiry->inquiryTreatments as $inquiry_treatment) {
                $desc_treatment_name = $inquiry_treatment->treatmentParam->treatment->name;
                $description_params[$inquiry_treatment->treatmentParam->id] = $inquiry_treatment->treatmentParam->value;

                if ($inquiry_treatment->severity_id) {
                    $treatment_severity = $inquiry_treatment->getTreatmentSeveritiesByParam();
                    foreach ($treatment_severity as $severity) {
                        $description_params[$inquiry_treatment->treatmentParam->id] .= '(' . $severity->brandParam->brand->name . ', '. $severity->count * $inquiry_treatment->session->session_count . ')';
                    }
                } elseif ($inquiry_treatment->treatment_intensity_id) {
                    $treatment_intensity = $inquiry_treatment->treatmentIntensity;
                    $description_params[$inquiry_treatment->treatmentParam->id] .= '(' . $treatment_intensity->brandParam->brand->name . ', '. $treatment_intensity->count * $inquiry_treatment->session->session_count . ')';
                } elseif (!$inquiry_treatment->treatment_intensity_id && !$inquiry_treatment->severity_id) {
                    $description_params[$inquiry_treatment->treatmentParam->id] = '(' . $inquiry_treatment->treatmentParam->treatment->name . ')';
                }
            }

            return $desc_treatment_name . ': ' . implode(', ', $description_params);

        } elseif ($inquiry->inquiryBrands) {
            foreach ($inquiry->inquiryBrands as $inquiry_brand) {
                $desc_brand_name = $inquiry_brand->brandParam->brand->name;
                $description_params[$inquiry_brand->brandParam->id] = $inquiry_brand->brandParam->value;
            }

            return $desc_brand_name . ': ' . implode(', ', $description_params);
        } else {
            return false;
        }
    }

    public function getDescriptionArray(Payment $payment)
    {
        $offer_list = InquiryDoctorList::find()
            ->where(['inquiry_id' => $payment->inquiry_id])
            ->andWhere(['status' => InquiryDoctorList::STATUS_FINALIZED])
            ->all();

        $inquiry = Inquiry::findOne($payment->inquiry_id);

        if ($inquiry->inquiryTreatments) {
            foreach ($offer_list as $inquiry_item) {

                /** @var InquiryDoctorList $inquiry_item */
                $desc_treatment_name = $inquiry_item->treatmentParam->treatment->name;
                $treatment[$inquiry_item->treatmentParam->id] = [
                    'param' => $desc_treatment_name . ', ' . $inquiry_item->treatmentParam->value,
                    'price' => $inquiry_item->price,
                ];

                $inquiry_treatment = $inquiry_item->inquiryTreatment;

                if ($inquiry_treatment->severity_id) {

                    $treatment_severity = $inquiry_treatment->getTreatmentSeveritiesByParam();
                    foreach ($treatment_severity as $severity) {
                        $treatment[$inquiry_item->treatmentParam->id]['used_brands'] = $severity->brandParam->brand->name . ', '. $severity->count * $inquiry_treatment->session->session_count;
                    }

                } elseif ($inquiry_treatment->treatment_intensity_id) {

                    $treatment_intensity = $inquiry_treatment->treatmentIntensity;
                    $treatment[$inquiry_item->treatmentParam->id]['used_brands'] = $treatment_intensity->brandParam->brand->name . ', '. $treatment_intensity->count * $inquiry_treatment->session->session_count;
                }
            }

            $this->treatment_array = $treatment;

        } elseif ($inquiry->inquiryBrands) {

            /** @var InquiryDoctorList $inquiry_item */
            foreach ($offer_list as $inquiry_item) {

                $brand[$inquiry_item->brandParam->id] = [
                    'param' => $inquiry_item->brandParam->brand->name,
                    'used_brands' => $inquiry_item->brandParam->value,
                    'price' => $inquiry_item->price
                ];
            }

            $this->brand_array = $brand;

        } else {
            return false;
        }
    }
}
