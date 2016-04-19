<?php
/**
 * Created by PhpStorm.
 * User: kharalampidi
 * Date: 18.01.16
 * Time: 19:30
 */

namespace frontend\modules\api\v1\controllers;


use common\commands\command\SendEmailCommand;
use common\models\UsePromoCode;
use common\models\User;
use frontend\modules\api\v1\models\PromoCode;
use frontend\modules\api\v1\resources\ModelError;
use Yii;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;
use yii\filters\VerbFilter;
use yii\rest\Controller;

class PromoCodeController extends Controller
{
    /**
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['authenticator'] = [
            'class' => CompositeAuth::className(),
            'except'=>['login','signup'],
            'authMethods' => [
                [
                    'class' => HttpBasicAuth::className(),
                    'auth' => function ($username, $password) {
                        $user = User::findByLogin($username);
                        /* @var $user User */
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
                'invite' => ['post']
            ]
        ];
        return $behaviors;
    }


    public function actionInvite()
    {
        $model = new PromoCode();
        $model->load(\Yii::$app->request->post(),'');
        if ($model->validate(['email'])) {
            $data = $model->generateRegistrationPromo(\Yii::$app->user->id, $model->email);
            if (!empty($model->email)) {
                Yii::$app->commandBus->handle(new SendEmailCommand([
                    'from' => [Yii::$app->params['adminEmail'] => Yii::$app->name],
                    'to' => $model->email,
                    'subject' => Yii::t('frontend', 'Invite to {app_name}',['app_name'=>Yii::$app->name]),
                    'view' => 'promoCode',
                    'params' => [
                        'model' => $data,
                        'mailing_address' => getenv('ADMIN_EMAIL'),
                        'current_year' => date('Y'),
                        'app_name' => Yii::$app->name,
                    ]
                ]));
                return Yii::t('app', 'Invitation email has been sent');
            } else {
                return ['promo_code' => $data->text];
            }


        } else {
            return ModelError::get($model);
        }
    }

    /**
     * @return array
     */
    public function actionCheck()
    {
        $model = new UsePromoCode();

        $model->load(Yii::$app->request->post(),'');
        if ($model->validate()) {
            /** @var PromoCode $model */
            $model = PromoCode::find()->where(['LIKE BINARY', 'text', Yii::$app->request->post('promo_code')])->one();
            return $promo_info = [
                'discount_size' => $model->value,
                'message' => Yii::t('app', 'Promo code applied')
            ];
        } else {
            \Yii::$app->response->setStatusCode(422);
        }
        return $model->errors;
    }

//    public function actionUse()
//    {
//        $model = new UsePromoCode();
//
//        $model->load(Yii::$app->request->post(),'');
//        if ($model->validate()) {
//            $use_result = $model->useCode();
//            if ($use_result) {
//               return $use_result->value;
//            } else {
//                \Yii::$app->response->setStatusCode(422);
//            }
//        }
//        return $model->errors;
//    }
}