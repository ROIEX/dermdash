<?php
namespace frontend\modules\api\v1\models;

use common\components\Mandrill;
use common\models\InquiryDoctorList;
use Yii;
use yii\base\Model;
use yii\web\BadRequestHttpException;

class Booking extends Model
{
    public $inquiry_doctor_id;
    public $first_name;
    public $last_name;
    public $phone_number;
    public $date;
    public $email;

    public function rules()
    {
        return [
            [['inquiry_doctor_id', 'first_name', 'last_name', 'email', 'phone_number', 'date'],'required'],
            ['email', 'email'],
            ['date', 'date', 'format' => 'MM/dd/yyyy HH:mm'],
            [['inquiry_doctor_id'], 'checkInquiry'],
            [['first_name', 'last_name', 'phone_number'], 'string'],
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
                    $this->addError('inquiry_doctor_id', Yii::t('app', 'This is not your inquiry doctor.'));
                } elseif ($model->status == $model::STATUS_FINALIZED) {
                    $this->addError('inquiry_doctor_id', Yii::t('app', 'You can`t book paid offer.'));
                } elseif($model->status == $model::STATUS_BOOKED) {
                    $this->addError('inquiry_doctor_id', Yii::t('app', 'Already booked.'));
                }
            }
        } else {
            $this->addError('inquiry_doctor_id', Yii::t('app', 'Inquiry doctor not found.'));
        }
    }

    public function book()
    {
        $booked_offers = InquiryDoctorList::findAll($this->inquiry_doctor_id);
        if (!empty($booked_offers)) {
            foreach ($booked_offers as $offer) {
                $offer->status = $offer::STATUS_BOOKED;
                $offer->update(false);
            }

            $booking = new \common\models\Booking();
            $booking->date = $this->date;
            $booking->phone_number = $this->phone_number;
            $booking->email = $this->email;
            $booking->first_name = $this->first_name;
            $booking->last_name = $this->last_name;
            $booking->inquiry_id = $booked_offers[0]->inquiry_id;
            
            if ($booking->save(false) && $this->sendMail()) {
                return true;
            }
            
            return false;
        } else {
            throw new BadRequestHttpException;
        }
    }
    
    public function sendMail()
    {
        $mandrill = new Mandrill(Yii::$app->params['mandrillApiKey']);
        return true;
//        $patient_message = [
//            'to' => [
//                [
//                    'email' => $patient_email,
//                    'name' => $patient_email,
//                ]
//            ],
//            "merge_language" => "mailchimp",
//            "merge" => true,
//            'merge_vars' => [
//                [
//                    'rcpt' => $patient_email,
//                    'vars' => [
//                        [
//                            'name' => 'list_address_html',
//                            'content' => getenv('ADMIN_EMAIL'),
//                        ],
//                        [
//                            'name' => 'invoice_item',
//                            'content' => $item,
//                        ],
//                        [
//                            'name' => 'current_year',
//                            'content' => date('Y'),
//                        ],
//                        [
//                            'name' => 'company',
//                            'content' => Yii::$app->name,
//                        ],
//                        [
//                            'name' => 'rewards',
//                            'content' => Yii::$app->user->identity->userProfile->reward ? Yii::$app->user->identity->userProfile->reward : 'no rewards',
//                        ],
//                        [
//                            'name' => 'invoice_number',
//                            'content' => $list_model->inquiry_id,
//                        ],
//                        [
//                            'name' => 'doctor_email',
//                            'content' => $doctor->user->email,
//                        ],
//                        [
//                            'name' => 'doctor_clinic',
//                            'content' => $doctor->clinic,
//                        ],
//                        [
//                            'name' => 'doctor_phone',
//                            'content' => $doctor->profile->phone,
//                        ],
//                        [
//                            'name' => 'doctor_website',
//                            'content' => $doctor->website,
//                        ],
//                        [
//                            'name' => 'doctor_address',
//                            'content' => $doctor->profile->address . '<br>' . $doctor->profile->city . ', ' . $doctor->profile->state->short_name . ', ' . $doctor->profile->zipcode,
//                        ],
//
//
//                    ]
//                ]
//            ],
//        ];
//        $doctor_message = [
//            'to' => [
//                [
//                    'email' => $doctor->user->email,
//                    'name' => $doctor->user->email,
//                ]
//            ],
//            "merge_language" => "mailchimp",
//            "merge" => true,
//            'merge_vars' => [
//                [
//                    'rcpt' => $doctor->user->email,
//                    'vars' => [
//                        [
//                            'name' => 'list_address_html',
//                            'content' => getenv('ADMIN_EMAIL'),
//                        ],
//                        [
//                            'name' => 'company',
//                            'content' => Yii::$app->name,
//                        ],
//                        [
//                            'name' => 'current_year',
//                            'content' => date('Y'),
//                        ],
//                        [
//                            'name' => 'patient_name',
//                            'content' => $model->first_name . ' ' . $model->last_name,
//                        ],
//                        [
//                            'name' => 'invoice_number',
//                            'content' => (string)$list_model->inquiry_id,
//                        ],
//                        [
//                            'name' => 'patient_email',
//                            'content' => Yii::$app->user->identity->email,
//                        ],
//                        [
//                            'name' => 'patient_phone',
//                            'content' => Yii::$app->user->identity->userProfile->phone ? Yii::$app->user->identity->userProfile->phone : 'No phone provided',
//                        ],
//                        [
//                            'name' => 'invoice_item',
//                            'content' => $item,
//                        ],
//
//                    ]
//                ]
//            ],
//        ];
//        $mandrill->messages->sendTemplate('Patient Receipt', [] , $patient_message);
//        $mandrill->messages->sendTemplate('Doctor Receipt', [] , $doctor_message);
    }
}