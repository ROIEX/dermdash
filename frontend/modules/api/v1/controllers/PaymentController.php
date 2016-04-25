<?php
namespace frontend\modules\api\v1\controllers;

use common\models\Doctor;
use common\models\InquiryDoctorList;
use common\models\State;
use common\models\User;
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
        if ($model->validate()) {
            if ($model->pay()) {

                $list_models = InquiryDoctorList::findAll($model->inquiry_doctor_id);

                /** @var InquiryDoctorList $list_model */
                foreach ($list_models as $list_model) {
                    $doctor = Doctor::findOne(['user_id' => $list_model->user_id]);
                    $item = $list_model->inquiry->getInquiryItem();

                    Yii::$app->mailer->compose('patient_inquiry_payment', [
                        'mailing_address' => getenv('ADMIN_EMAIL'),
                        'current_year' => date('Y'),
                        'app_name' => Yii::$app->name,
                        'rewards' => round(Yii::$app->user->identity->userProfile->reward, 2),
                        'doctor_address' => $doctor->profile->address,
                        'doctor_city' => $doctor->profile->city,
                        'doctor_zip' => $doctor->profile->zipcode,
                        'doctor_website' => $doctor->website,
                        'doctor_phone' => $doctor->profile->phone,
                        'doctor_email' => $doctor->user->email,
                        'doctor_state' => State::getShortName($doctor->profile->state_id),
                        'invoice_number' => $list_model->inquiry_id,
                        'item' => $item

                    ])
                        ->setTo(Yii::$app->user->identity->email)
                        ->setSubject(Yii::t('app', 'Inquiry payment'))
                        ->send();


                    Yii::$app->mailer->compose('doctor_inquiry_payment', [
                        'mailing_address' => getenv('ADMIN_EMAIL'),
                        'current_year' => date('Y'),
                        'app_name' => Yii::$app->name,
                        'first_name' => Yii::$app->user->identity->userProfile->firstname,
                        'last_name' => Yii::$app->user->identity->userProfile->lastname,
                        'phone' => Yii::$app->user->identity->userProfile->phone,
                        'email' => Yii::$app->user->identity->email,
                        'invoice_number' => $list_model->inquiry_id,
                        'item' => $item

                    ])
                        ->setTo($doctor->user->email)
                        ->setSubject(Yii::t('app', 'Inquiry payment'))
                        ->send();
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