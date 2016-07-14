<?php
namespace frontend\modules\api\v1\controllers;

use common\components\Mandrill;
use common\models\Doctor;
use common\models\InquiryDoctorList;
use common\models\User;
use frontend\modules\api\v1\models\DiscountWithPromo;
use frontend\modules\api\v1\models\Payment;
use frontend\modules\api\v1\resources\ModelError;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;
use yii\filters\VerbFilter;
use yii\helpers\BaseInflector;
use yii\rest\Controller;
use yii\web\BadRequestHttpException;
use Yii;

class PaymentController extends Controller
{
    /**
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => CompositeAuth::className(),
            'authMethods' => [
                [
                    'class' => HttpBasicAuth::className(),
                    'auth' => function ($username, $password) {
                        $user = User::findByLogin($username);
                        return $user->validatePassword($password)
                            ? $user
                            : null;
                    }
                ],
                HttpBearerAuth::className(),
                QueryParamAuth::className()
            ]
        ];
        $behaviors['verbs'] = [
            'class' => VerbFilter::className(),
            'actions' => [
                'pay' => ['post'],
                'get-reward' => ['get'],
            ]
        ];

        return $behaviors;
    }

    /**
     * @return array
     * @throws BadRequestHttpException
     */
    public function actionPay()
    {
        $model = new Payment();
        $model->load(\Yii::$app->request->post(),'');

        if ($model->payment_type == Payment::REGULAR_PAYMENT) {
            $model->scenario = Payment::SCENARIO_REGULAR;
        } elseif($model->payment_type == Payment::APPOINTMENT_PAYMENT) {
            $model->scenario = Payment::SCENARIO_APPOINTMENT;
        } else {
            throw new BadRequestHttpException;
        }

        $mandrill = new Mandrill(Yii::$app->params['mandrillApiKey']);
        $patient_email = Yii::$app->user->identity->email;

        if ($model->validate()) {
            if ($model->pay()) {

                $list_models = InquiryDoctorList::findAll($model->inquiry_doctor_id);

                /** @var InquiryDoctorList $list_model */
                foreach ($list_models as $list_model) {
                    /** @var Doctor $doctor */
                    $doctor = Doctor::findOne(['user_id' => $list_model->user_id]);
                    $offer = $list_model->inquiry->getOfferData($list_model->inquiry_id, $list_model->user_id);
                    
                    foreach ($offer[$list_model->user_id]['data'] as $offer_data) {
                        if(isset($offer_data['brand'])) {
                            $item = Yii::t('app', 'Item: ') . $offer_data['brand'] . ', ';
                            if ((is_numeric((int)$offer_data['param_value']))) {
                                $item .= (int)$offer_data['param_value'] > 1 ?
                                    (BaseInflector::pluralize($offer_data['param_name']) . ': ' . $offer_data['param_value']) :
                                    ($offer_data['param_name'] . ' ' . $offer_data['param_value']);
                            } else {
                                $item .= $offer_data['param_name'] . ': ' . $offer_data['param_value'];
                            }
                        } else {
                            $item = Yii::t('app', 'Treatment: {item}', ['item' => $list_model->inquiry->getInquiryItem()]) . '<br>';
                            $item .= Yii::t('app', 'Area: ') . $offer_data['param'] . '<br>';
                            if ($list_model->inquiry->getInquiryItem() != $offer_data['procedure_name']) {
                                $item .= Yii::t('app', 'Used brand: ') . $offer_data['procedure_name'] . '<br>';
                            }
                            $item .= (int)$offer_data['amount'] > 1 ?
                                (BaseInflector::pluralize($offer_data['param_name']) . ': ' . $offer_data['amount'] . '<br>') :
                                ($offer_data['param_name'] . ': ' . $offer_data['amount'] . '<br>');
                        }
                    }

//
//                    $patient_message = [
//                        'to' => [
//                            [
//                                'email' => $patient_email,
//                                'name' => $patient_email,
//                            ]
//                        ],
//                        "merge_language" => "mailchimp",
//                        "merge" => true,
//                        'merge_vars' => [
//                            [
//                                'rcpt' => $patient_email,
//                                'vars' => [
//                                    [
//                                        'name' => 'list_address_html',
//                                        'content' => getenv('ADMIN_EMAIL'),
//                                    ],
//                                    [
//                                        'name' => 'invoice_item',
//                                        'content' => $item,
//                                    ],
//                                    [
//                                        'name' => 'current_year',
//                                        'content' => date('Y'),
//                                    ],
//                                    [
//                                        'name' => 'company',
//                                        'content' => Yii::$app->name,
//                                    ],
//                                    [
//                                        'name' => 'rewards',
//                                        'content' => Yii::$app->user->identity->userProfile->reward ? Yii::$app->user->identity->userProfile->reward : 'no rewards',
//                                    ],
//                                    [
//                                        'name' => 'invoice_number',
//                                        'content' => $list_model->inquiry_id,
//                                    ],
//                                    [
//                                        'name' => 'doctor_email',
//                                        'content' => $doctor->user->email,
//                                    ],
//                                    [
//                                        'name' => 'doctor_clinic',
//                                        'content' => $doctor->clinic,
//                                    ],
//                                    [
//                                        'name' => 'doctor_phone',
//                                        'content' => $doctor->profile->phone,
//                                    ],
//                                    [
//                                        'name' => 'doctor_website',
//                                        'content' => $doctor->website,
//                                    ],
//                                    [
//                                        'name' => 'doctor_address',
//                                        'content' => $doctor->profile->address . '<br>' . $doctor->profile->city . ', ' . $doctor->profile->state->short_name . ', ' . $doctor->profile->zipcode,
//                                    ],
//
//
//                                ]
//                            ]
//                        ],
//                    ];
//                    $doctor_message = [
//                        'to' => [
//                            [
//                                'email' => $doctor->user->email,
//                                'name' => $doctor->user->email,
//                            ]
//                        ],
//                        "merge_language" => "mailchimp",
//                        "merge" => true,
//                        'merge_vars' => [
//                            [
//                                'rcpt' => $doctor->user->email,
//                                'vars' => [
//                                    [
//                                        'name' => 'list_address_html',
//                                        'content' => getenv('ADMIN_EMAIL'),
//                                    ],
//                                    [
//                                        'name' => 'company',
//                                        'content' => Yii::$app->name,
//                                    ],
//                                    [
//                                        'name' => 'current_year',
//                                        'content' => date('Y'),
//                                    ],
//                                    [
//                                        'name' => 'patient_name',
//                                        'content' => $model->first_name . ' ' . $model->last_name,
//                                    ],
//                                    [
//                                        'name' => 'invoice_number',
//                                        'content' => (string)$list_model->inquiry_id,
//                                    ],
//                                    [
//                                        'name' => 'patient_email',
//                                        'content' => Yii::$app->user->identity->email,
//                                    ],
//                                    [
//                                        'name' => 'patient_phone',
//                                        'content' => Yii::$app->user->identity->userProfile->phone ? Yii::$app->user->identity->userProfile->phone : 'No phone provided',
//                                    ],
//                                    [
//                                        'name' => 'invoice_item',
//                                        'content' => $item,
//                                    ],
//
//                                ]
//                            ]
//                        ],
//                    ];
//
//                    $mandrill->messages->sendTemplate('Patient Receipt', [] , $patient_message);
//                    $mandrill->messages->sendTemplate('Doctor Receipt', [] , $doctor_message);

                }
                return ['success'];
            } else {
                return ['errors'];
            }
        }
        return ModelError::get($model);
    }

    /**
     * @return array
     */
    public function actionGetReward()
    {
        return [
            'reward_count'=>\Yii::$app->user->identity->getBonusesCount()
        ];
    }

    /**
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionGetDiscount()
    {
        $model = new DiscountWithPromo();
        if ($model->load(\Yii::$app->request->get(),'')) {
            return $model->getDiscount();
        } else {
            throw new BadRequestHttpException;
        }
    }
}