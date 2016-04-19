<?php

namespace backend\controllers;


use common\models\MailchimpList;
use common\models\User;
use DrewM\MailChimp\MailChimp;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class MailChimpController extends Controller
{
    public function actionIndex()
    {
        $model = new MailchimpList();
        $dataProvider = new ArrayDataProvider([
            'allModels'=>ArrayHelper::toArray($model->findAll())
        ]);
        return $this->render('index',[
            'dataProvider'=>$dataProvider
        ]);
    }

    public function actionView($id)
    {
        $model = new MailchimpList();
        $mailchimpList = $model->findOne($id);
        if ($mailchimpList == null) {
            throw new NotFoundHttpException;
        }
        return $this->render('view',[
            'model'=> $mailchimpList
        ]);
    }

    public function actionCreate()
    {
        $model = new MailchimpList();
        if ($model->load(\Yii::$app->request->post())) {
            $mc = new Mailchimp('b1762d7060188e92ddada3470d6f55bb-us12');
            $result = $mc->post('lists',$model->createJson());
            return $this->redirect(['view','id'=>$result['id']]);
        }
        return $this->render('create',['model'=>$model]);
    }

    public function actionDelete($id)
    {
        $model = new MailchimpList();
        if ($model == null) {
            throw new NotFoundHttpException;
        }
        $model->delete($id);
        return $this->redirect(['index']);
    }

    public function actionUpdate($id)
    {
        $model = new MailchimpList();
        $model->findOne($id);
        if ($model == null) {
            throw new NotFoundHttpException;
        }
        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            $model->update();
            return $this->redirect(['view','id'=>$id]);
        }
        return $this->render('create',['model'=>$model]);
    }

    public function actionImport($list_id)
    {
        $model = new MailchimpList();
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $model->importMembers(User::find()->all(),$list_id);
        return $this->redirect(['view','id'=>$list_id]);
    }

    public function actionCheckBatch($batch_id)
    {
        $model = new MailchimpList();
        \Yii::$app->response->format = Response::FORMAT_JSON;
        return [
            'status'=>$model->checkBatch($batch_id)
        ];
    }

    public function actionMembers($list_id)
    {
        $model = new MailchimpList();
        $model = $model->findOne($list_id);
        var_dump($model->members());
    }
}