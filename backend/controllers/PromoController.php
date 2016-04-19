<?php

namespace backend\controllers;

use common\models\PromoUsed;
use common\models\User;
use Yii;
use common\models\PromoCode;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\commands\command\SendEmailCommand;

/**
 * PromoController implements the CRUD actions for PromoCode model.
 */
class PromoController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all PromoCode models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => PromoCode::find()
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PromoCode model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new PromoCode model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new PromoCode();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
//            if (!empty($model->user_id)) {
//                Yii::$app->commandBus->handle(new SendEmailCommand([
//                    'from' => [Yii::$app->params['adminEmail'] => Yii::$app->name],
//                    'to' => User::findOne([$model->user_id])->email,
//                    'subject' => Yii::t('app', 'Invite to {app_name}',['app_name'=>Yii::$app->name]),
//                    'view' => 'promoCode',
//                    'params' => [
//                        'model' => $model,
//                        'mailing_address' => getenv('ADMIN_EMAIL'),
//                        'current_year' => date('Y'),
//                        'app_name' => Yii::$app->name,
//                    ]
//                ]));
//                Yii::$app->session->setFlash('alert', [
//                    'options' => ['class' => 'alert-success'],
//                    'body' => Yii::t('app', 'Email with code has been sent to selected user')
//                ]);
//            }
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing PromoCode model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing PromoCode model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionStatistic($id, $used_while)
    {
        $dataProvider = new ActiveDataProvider([
            'query' => PromoUsed::find()
                ->where(['promo_used.promo_id' => $id])
                ->andWhere(['used_while' => $used_while])
                ->join('LEFT JOIN', 'user as u', 'u.id = promo_used.user_id'),
        ]);
        return $this->render('user-list', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Finds the PromoCode model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PromoCode the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PromoCode::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
