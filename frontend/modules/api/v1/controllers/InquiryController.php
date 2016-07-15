<?php
/**
 * Created by PhpStorm.
 * User: rexit
 * Date: 29.12.15
 * Time: 18:18
 */

namespace frontend\modules\api\v1\controllers;


use common\models\InquiryDoctorList;
use frontend\modules\api\v1\models\Booking;
use frontend\modules\api\v1\models\DoctorOffer;
use frontend\modules\api\v1\models\GetDoctorList;
use frontend\modules\api\v1\models\Inquiry;

use frontend\modules\api\v1\models\PaymentHistory;
use common\models\User;
use frontend\modules\api\v1\resources\ModelError;
use Yii;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;
use yii\helpers\ArrayHelper;
use yii\rest\Controller;

class InquiryController extends Controller
{
    /**
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['authenticator'] = [
            'class' => CompositeAuth::className(),

            'except' => ['offer-search'],
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

        return $behaviors;
    }

    /**
     * @return array
     */
    public function actionCreate()
    {
        $model = new Inquiry();
        if ($model->load(Yii::$app->request->post(), '') && $model->validate()) {
            $model->setNeededScenario();
            $model->load(Yii::$app->request->post(), '');
            if ($model->validate()) {
                if ($model->save()) {
//                    Yii::$app->mailer->compose('patient_inquiry_submit', [
//                        'mailing_address' => getenv('ADMIN_EMAIL'),
//                        'current_year' => date('Y'),
//                        'app_name' => Yii::$app->name,
//                        'rewards' => round(Yii::$app->user->identity->userProfile->reward, 2)])
//                        ->setTo(Yii::$app->user->identity->email)
//                        ->setSubject(Yii::t('app', 'Inquiry submitting'))
//                        ->send();
                    return [
                        'inquiry_id' => $model->id
                    ];
                }
                return $model->errors;
            }
        }
        return $model->errors;
    }

    /**
     * @return array
     */
    public function actionOfferSearch()
    {
        $model = new Inquiry();
        if ($model->load(Yii::$app->request->post(), '') && $model->validate()) {
            $model->setNeededScenario();
            if (Yii::$app->user->isGuest) {
                Yii::$app->user->login(User::findOne(User::GUEST_ACCOUNT_ID));
            }
            if ($model->validate()) {
                if ($model->save()) {
                    $list_model = new GetDoctorList();
                    $list_model->inquiry_id = $model->id;
                    $result = $list_model->getDoctorList();
                    if (Yii::$app->user->identity->id != User::GUEST_ACCOUNT_ID) {
                        Yii::$app->user->logout();
                    }
                    return $result;
                }
            }
            return $model->errors;
        }
        return $model->errors;
    }

    /**
     * @return array
     */
    public function actionGetDoctorList()
    {
        $model = new GetDoctorList();
        $model->load(Yii::$app->request->post(), '');
        if ($model->validate()) {
            return $model->getDoctorList();
        }
        return $model->errors;
    }

    /**
     * @return array
     */
    public function actionGetDoctorOffers()
    {
        $model = new DoctorOffer();
        if ($model->load(Yii::$app->request->post(), '') && $model->validate()) {
            return $model->data();
        }
        return $model->errors;
    }

    /**
     * @return int|string
     */
    public function actionGetNewOffers()
    {

        $inquiry = new \common\models\Inquiry();
        $abandoned_offers = $inquiry->getAbandonedInquiryList()
            ->andWhere(['!=', 'list.is_viewed_by_patient', InquiryDoctorList::VIEWED_STATUS_YES])
            ->all();

        if (!empty($abandoned_offers)) {
            $inquiry_id_list = ArrayHelper::map($abandoned_offers, 'id', 'id');
            InquiryDoctorList::updateAll(['is_viewed_by_patient' => InquiryDoctorList::VIEWED_STATUS_YES], ['in', 'inquiry_id', $inquiry_id_list]);
        }

        $unviewed_count = InquiryDoctorList::find()
            ->join('LEFT JOIN', 'inquiry as inquiry', 'inquiry.id = inquiry_doctor_list.inquiry_id')
            ->where(['inquiry.user_id' => Yii::$app->user->id])
            ->andWhere(['inquiry_doctor_list.is_viewed_by_patient' => InquiryDoctorList::VIEWED_STATUS_NO])
            ->count();
        return $unviewed_count;
    }
    
    /**
     * @return array
     */
    public function actionGetHistory()
    {
        return PaymentHistory::getHistory();
    }

    /**
     * @return array
     */
    public function actionHistory()
    {
        $model = \common\models\Inquiry::find()
            ->where(['>','created_at',(time() - \common\models\Inquiry::INQUIRY_DAYS_ACTIVE * 3600 * 24)])
            ->andWhere(['user_id'=>Yii::$app->user->id])
            ->all();

        $data = [];
        foreach ($model as $one) {
            /* @var $one \common\models\Inquiry */
            if ($one->type == $one::TYPE_TREATMENT) {

                $item = $one->inquiryTreatments[0]->treatmentParam->treatment;
                $icon = $item->getPhoto($item->icon_base_url, $item->icon_path);
                $procedureName = $item->name;
            } else {
                $brand = $one->inquiryBrands[0]->brandParam->brand;
                $icon = $brand->getPhoto($brand->icon_base_url, $brand->icon_path);
                $procedureName = $brand->name;
            }
            if (empty($one->doctorAccepted)) {
                $data[] = [
                    'inquiry_id'=>$one->id,
                    'visit_date'=>$one->created_at,
                    'icon'=>$icon,
                    'type'=>$one->type,
                    'procedure_name'=>$procedureName
                ];
            }
        }
        return $data;
    }
    
    public function actionBook()
    {
        $model = new Booking();
        $model->load(\Yii::$app->request->post(),'');
        if ($model->validate()) {
            if ($model->book()) {
                
                return ['success'];
            };
            return ['error'];
        }
        return ModelError::get($model);
    }
}