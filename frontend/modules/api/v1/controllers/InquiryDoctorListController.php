<?php
/**
 * Created by PhpStorm.
 * User: rexit
 * Date: 29.12.15
 * Time: 18:19
 */

namespace frontend\modules\api\v1\controllers;


use common\models\User;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;
use yii\rest\ActiveController;

class InquiryDoctorListController extends ActiveController
{
    public function actions()
    {
        $actions = parent::actions();

        // Delete actions
        unset($actions['delete'], $actions['update'],$actions['index']);
        return $actions;
    }

    /**
     * @var string
     */
    public $modelClass = 'common\models\InquiryDoctorList';
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
}