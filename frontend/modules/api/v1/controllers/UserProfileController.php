<?php

namespace frontend\modules\api\v1\controllers;


use common\models\State;
use common\models\User;
use common\models\UserProfile;
use frontend\modules\api\v1\resources\ModelError;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;
use yii\filters\VerbFilter;
use yii\rest\Controller;

class UserProfileController extends Controller
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
                    },
                ],
                HttpBearerAuth::className(),
                QueryParamAuth::className()
            ]
        ];
        $behaviors['verbs'] = [
            'class' => VerbFilter::className(),
            'actions' => [
                'update' => ['post'],
                'me' => ['get'],
            ]
        ];
        return $behaviors;
    }


    /**
     * @return array|UserProfile|null|static
     * @throws \yii\base\InvalidConfigException
     */
    public function actionUpdate()
    {
        $model = \frontend\modules\api\v1\resources\UserProfile::findOne(['user_id'=>\Yii::$app->user->id]);
        /* @var $model UserProfile */
        $model->load(\Yii::$app->request->post(), '');
        $model->scenario = UserProfile::PATIENT_PROFILE;

        if (!$model->validate()) {
            \Yii::$app->response->setStatusCode(409);
            return $model->errors;
        }

        $model->state_id = State::getStateIdByShortName($model->address_info['STATE']);
        $model->city = $model->address_info['CITY'];

        if ($model->validate()) {
            if (!is_null($model->state_notification)) {
                $model->scenario = UserProfile::SCENARIO_NOTIFICATION;
            }

            if (!empty($model->email)) {
                $user = \Yii::$app->user->identity;
                $user->email = $model->email;
                if ($user->validate()) {
                    $user->username = $model->email;
                    $user->save(false);
                    $model->save();
                } else {
                    \Yii::$app->response->setStatusCode(409);
                    return $user->errors;
                }
            }

            if ($model->date_of_birth) {
                $model->date_of_birth = date('m/d/Y', strtotime($model->date_of_birth));
            }
            return $model;
        }

        return ModelError::get($model);
    }

    /**
     * Return user profile data of current user
     * @return null|static
     */
    public function actionMe()
    {
        return \frontend\modules\api\v1\resources\UserProfile::findOne(getMyId());
    }
}