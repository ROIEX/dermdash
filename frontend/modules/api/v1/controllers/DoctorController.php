<?php
/**
 * Created by PhpStorm.
 * User: rexit
 * Date: 28.12.15
 * Time: 19:58
 */

namespace frontend\modules\api\v1\controllers;


use common\models\User;
use frontend\modules\api\v1\resources\Doctor;
use yii\data\ActiveDataProvider;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;
use yii\filters\VerbFilter;
use yii\rest\ActiveController;
use yii\web\NotFoundHttpException;

class DoctorController extends ActiveController
{
    /**
     * @var string
     */
    public $modelClass = 'frontend\modules\api\v1\resources\Doctor';
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
            'except'=>['login','signup'],
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
     * @inheritdoc
     */
    public function actions()
    {
        return [
//            'index' => [
//                'class' => 'yii\rest\IndexAction',
//                'modelClass' => $this->modelClass
//            ],
            'view' => [
                'class' => 'yii\rest\ViewAction',
                'modelClass' => $this->modelClass,
                'findModel' => [$this, 'findModel']
            ],
        ];
    }

    /**
     * Return dataProvider with active doctors.
     * @return ActiveDataProvider
     */
    public function actionIndex(){
        $activeData = new ActiveDataProvider([
            'query' => Doctor::find()->active()->not_deleted(),
        ]);
        return $activeData;
    }


    /**
     * @param $id
     * @return null|static
     * @throws NotFoundHttpException
     */
    public function findModel($id)
    {
        $model = Doctor::find()->where(['id'=>$id])->active()->not_deleted()->one();
        if (!$model) {
            throw new NotFoundHttpException;
        }
        return $model;
    }
}