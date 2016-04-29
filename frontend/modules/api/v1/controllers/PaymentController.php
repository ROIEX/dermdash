<?php
namespace frontend\modules\api\v1\controllers;

use common\components\Mandrill;
use common\models\Doctor;
use common\models\InquiryDoctorList;
use common\models\State;
use common\models\User;
use DrewM\MailChimp\MailChimp;
use frontend\modules\api\v1\models\DiscountWithPromo;
use frontend\modules\api\v1\models\Payment;
use frontend\modules\api\v1\resources\ModelError;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;
use yii\filters\VerbFilter;
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
     */
    public function actionPay()
    {
        $model = new Payment();
        $model->load(\Yii::$app->request->post(),'');

        $mandrill = new Mandrill(Yii::$app->params['mandrillApiKey']);
        $patient_email = Yii::$app->user->identity->email;

        if (true) {
            if (true) {

                $list_models = InquiryDoctorList::findAll($model->inquiry_doctor_id);

                /** @var InquiryDoctorList $list_model */
                foreach ($list_models as $list_model) {
                    /** @var Doctor $doctor */
                    $doctor = Doctor::findOne(['user_id' => $list_model->user_id]);
                    $item = $list_model->inquiry->getInquiryItem();

                    $patient_message = [
                        'to' => [
                            [
                                'email' => $patient_email,
                                'name' => $patient_email,
                            ]
                        ],
                        'merge_vars' => [
                            [
                                'rcpt' => 'lol4toli1@gmail.com',
                                'vars' => [
                                    [
                                        'name' => 'list_address_html',
                                        'content' => getenv('ADMIN_EMAIL'),
                                    ],
                                    [
                                        'name' => 'current_year',
                                        'content' => date('Y'),
                                    ],
                                    [
                                        'name' => 'company',
                                        'content' => Yii::$app->name,
                                    ],
                                    [
                                        'name' => 'rewards',
                                        'content' => Yii::$app->user->identity->userProfile->reward,
                                    ],
                                    [
                                        'name' => 'invoice_number',
                                        'content' => $list_model->inquiry_id,
                                    ],
                                    [
                                        'name' => 'doctor_email',
                                        'content' => $doctor->user->email,
                                    ],
                                    [
                                        'name' => 'doctor_phone',
                                        'content' => $doctor->profile->phone,
                                    ],
                                    [
                                        'name' => 'doctor_website',
                                        'content' => $doctor->website,
                                    ],
                                    [
                                        'name' => 'doctor_address',
                                        'content' => $doctor->profile->address . '</br>' . $doctor->profile->city . ', ' . $doctor->profile->state->short_name . '</br>' . $doctor->profile->zipcode,
                                    ],
                                    [
                                        'name' => 'invoice_item',
                                        'content' => $item,
                                    ],

                                ]
                            ]
                        ],
                    ];
                    $doctor_message = [
                        'to' => [
                            [
                                'email' => $patient_email,
                                'name' => $patient_email,
                            ]
                        ],
                        'merge_vars' => [
                            [
                                'rcpt' => $patient_email,
                                'vars' => [
                                    [
                                        'name' => 'list_address_html',
                                        'content' => getenv('ADMIN_EMAIL'),
                                    ],
                                    [
                                        'name' => 'current_year',
                                        'content' => date('Y'),
                                    ],
                                    [
                                        'name' => 'patient_name',
                                        'content' => Yii::$app->user->identity->userProfile->firstname . ' ' . Yii::$app->user->identity->userProfile->lastname ,
                                    ],
                                    [
                                        'name' => 'invoice_number',
                                        'content' => $list_model->inquiry_id,
                                    ],
                                    [
                                        'name' => 'patient_email',
                                        'content' => Yii::$app->user->identity->email,
                                    ],
                                    [
                                        'name' => 'patient_phone',
                                        'content' => Yii::$app->user->identity->userProfile->phone,
                                    ],
                                    [
                                        'name' => 'invoice_item',
                                        'content' => $item,
                                    ],

                                ]
                            ]
                        ],
                    ];

                    $result['1'] = $mandrill->messages->sendTemplate('Patient Receipt', [] , $patient_message);
                    $result['2'] =$mandrill->messages->sendTemplate('Doctor Receipt', [] , $doctor_message);
return $result;
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