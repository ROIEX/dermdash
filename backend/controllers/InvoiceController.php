<?php

namespace backend\controllers;

use common\models\InvoiceGeneration;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * InvoiceController implements the CRUD actions for Invoice model.
 */
class InvoiceController extends Controller
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
     * Lists all InvoiceGeneration models.
     * @return mixed
     */
    public function actionIndex()
    {
        if (!Yii::$app->user->can('administrator')) {
            $query = InvoiceGeneration::find()->joinWith('doctorInvoices');
        } else {
            $query = InvoiceGeneration::find()->orderBy(['created_at' => SORT_DESC]);;
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    protected function findModel($id)
    {
        if (($model = InvoiceGeneration::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
