<?php

namespace frontend\modules\api\v1\controllers;


use common\models\User;
use common\models\UserDevice;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;
use yii\filters\VerbFilter;
use yii\rest\Controller;

class DeviceController extends Controller
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
                'index' => ['post']
            ]
        ];

        return $behaviors;
    }


    /**
     * @return array
     */
    public function actionIndex()
    {
        $model = new UserDevice();
        if ($model->load(\Yii::$app->request->post(),'') && $model->save()) {
            return [
                'massage'=>\Yii::t('app','Token successfully saved.')
            ];
        }
        return $model->errors;
    }
}