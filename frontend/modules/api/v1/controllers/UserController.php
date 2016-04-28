<?php

namespace frontend\modules\api\v1\controllers;

use common\models\User;
use frontend\modules\api\v1\resources\Login;
use frontend\modules\api\v1\resources\RestSignup;
use frontend\modules\api\v1\resources\SignUp;
use frontend\modules\api\v1\resources\User as UserResource;
use frontend\modules\user\models\PasswordResetRequestForm;
use Yii;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;
use yii\filters\VerbFilter;
use yii\rest\ActiveController;
use yii\web\BadRequestHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

/**
 * @author Eugene Terentev <eugene@terentev.net>
 */
class UserController extends ActiveController
{
    /**
     * @var string
     */
    public $modelClass = 'frontend\modules\api\v1\resources\User';
    public $serializer = [
        'class' => 'frontend\modules\api\v1\resources\RestSerializer',
    ];
    /**
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['authenticator'] = [
            'class' => CompositeAuth::className(),
            'except'=>['login','signup','request-password-reset'],
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
                'login' => ['post'],
                'signup' => ['post'],
                'request-password-reset' => ['post'],
            ]
        ];

        return $behaviors;
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'index' => [
                'class' => 'yii\rest\IndexAction',
                'modelClass' => $this->modelClass
            ],
            'view' => [
                'class' => 'yii\rest\ViewAction',
                'modelClass' => $this->modelClass,
                'findModel' => [$this, 'findModel']
            ],
            'options' => [
                'class' => 'yii\rest\OptionsAction'
            ]
        ];
    }

    /**
     * @param $id
     * @return null|static
     * @throws NotFoundHttpException
     */
    public function findModel($id)
    {
        /** @var UserResource $model */
        $model = UserResource::findOne($id);
        if (!$model) {
            throw new NotFoundHttpException;
        }
        return $model;
    }

    public function actionLogin()
    {
        $post = Yii::$app->request->post();
        if (empty($post['username']) || empty($post['password'])) {
            throw new BadRequestHttpException;
        }
        $model = Login::find()
            ->where(['username' => $post["username"]])
            ->orWhere(['email' => $post["username"]])
            ->andWhere(['status' => Login::STATUS_ACTIVE])
            ->one();
        /* @var $model User */
        if (empty($model)) {
            throw new NotFoundHttpException('User not found');
        }
        if ($model->validatePassword($post["password"])) {
            $model->logged_at = time();
            $model->save(false);
            return $model; //return whole user model including auth_key or you can just return $model["access_token"];
        } else {
            throw new ForbiddenHttpException();
        }
    }

    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        $model->load(Yii::$app->request->post(),'');
        if ($model->validate()) {
            $model->sendEmail();
        }
        return $model->getErrors();
    }

    public function actionSignup()
    {
        $model = new SignUp();
        $user = $model->signup();
        if ($user) {
            return [
                'username'=>$user->username,
                'access-token'=>$user->access_token
            ];
        } else {
            Yii::$app->response->setStatusCode(421);
            if (!empty($model->getErrors())) {
                return $model->getErrors();
            }
        }
        throw new BadRequestHttpException;
    }
}
