<?php

namespace backend\controllers;

use common\models\Inquiry;
use common\models\InquiryDoctorList;
use common\models\InquiryPhoto;
use common\models\Payment;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\helpers\Url;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;

class InquiryController extends Controller
{

    public function actionIndex($status)
    {
        $inquiry = new Inquiry();


        switch ($status) {

            case Inquiry::STATUS_PENDING:

                $query = $inquiry->getPendingInquiryList();
                $pending_offers = $query ->all();
                $pending_id_list = ArrayHelper::map($pending_offers, 'id', 'id');
                if (Yii::$app->user->can('administrator')) {
                    Inquiry::updateAll(['is_viewed_by_admin' => Inquiry::IS_VIEWED], ['id' => $pending_id_list]);
                }
                if (!Yii::$app->user->can('administrator')) {
                    $query->andWhere(['list.user_id' => Yii::$app->user->id]);
                    Inquiry::updateAll(['is_viewed' => Inquiry::IS_VIEWED], ['id' => $pending_id_list]);
                }
                break;

            case Inquiry::STATUS_COMPLETED:
                $completed_query = Payment::find()->where(['status' =>  Payment::STATUS_SUCCEEDED]);

                if (!Yii::$app->user->can('administrator')) {
                    $completed_query->andWhere(['doctor_id' => Yii::$app->user->id]);
                }

                $completed_offers = $completed_query->all();
                $completed_id_list = ArrayHelper::map($completed_offers, 'inquiry_id', 'inquiry_id');

                if (Yii::$app->user->can('administrator')) {
                    Inquiry::updateAll(['is_viewed_by_admin' => Inquiry::IS_VIEWED], ['id' => $completed_id_list]);
                } else {
                    Inquiry::updateAll(['is_viewed' => Inquiry::IS_VIEWED], ['id' => $completed_id_list]);
                }

                $query = $inquiry->find()->where(['in', 'id', $completed_id_list]);

                break;

            case Inquiry::STATUS_ABANDONED:
                $query = $inquiry->getAbandonedInquiryList();

                if (!Yii::$app->user->can('administrator')) {
                    $query->andWhere(['list.user_id' => Yii::$app->user->id]);
                } else {
                    $abandoned_inquiries = $query->all();
                    $abandoned_id_list = ArrayHelper::map($abandoned_inquiries, 'id', 'id');
                    Inquiry::updateAll(['is_viewed_by_admin' => Inquiry::IS_VIEWED], ['id' => $abandoned_id_list]);
                }

                break;

            default:
                throw new NotFoundHttpException('The requested page does not exist.');
                break;
        }

        $dataProvider = new ActiveDataProvider([
            'query'=> $query,
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'completed_page' => $status == Inquiry::STATUS_COMPLETED ? true : false,
        ]);
    }

    public function actionView()
    {
        if(Yii::$app->request->isAjax && Yii::$app->request->post('note_id')) {

            $model = $this->findModel((int)Yii::$app->request->post('note_id'));
            return $this->renderAjax('chart-note', [
                'model' => $model,
            ]);
        } else {
            $model = $this->findModel((int)Yii::$app->request->get('note_id'));
            if ($model->doctorIsParticipant) {

                $model->is_viewed = Inquiry::IS_VIEWED;
                $model->update(false);
                return $this->render('chart-note', [
                    'model' => $model,
                ]);
            } else {
                throw new NotFoundHttpException('The requested page does not exist.');
            }
        }
    }

    protected function findModel($id)
    {
        if (($model = Inquiry::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}