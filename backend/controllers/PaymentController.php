<?php

namespace backend\controllers;

use common\components\Invoice;
use common\components\SummaryInvoice;
use common\models\Inquiry;
use common\models\InvoiceGeneration;
use kartik\mpdf\Pdf;
use Yii;
use common\models\Payment;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PaymentController implements the CRUD actions for Payment model.
 */
class PaymentController extends Controller
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
     * Lists all Payment models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Payment::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Payment model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
//        $inquiry = $model->paymentItems ? $model->paymentItems[0]->doctorList->inquiry : false;
//        if ($inquiry) {
//            $invoice = new Invoice($inquiry, $model);
//        }

        return $this->render('view', [
            'model' => $model,
            //'inquiry' => $inquiry,
            //'invoice' => isset($invoice) ? $invoice : false,
        ]);
    }

    public function actionPaymentStatus()
    {
        if(Yii::$app->getrequest()->isAjax && Yii::$app->request->post('inquiry_id') !== null && Yii::$app->request->post('status_id') !== null) {
            $payment = Payment::findOne(['inquiry_id' => (int)Yii::$app->request->post('inquiry_id')]);

            if($payment) {
                if (Yii::$app->request->post('status_id') == Payment::OFFER_PENDING) {
                    $inquiry = Inquiry::findOne([Yii::$app->request->post('inquiry_id')]);
                    $inquiry->is_viewed = Inquiry::IS_NOT_VIEWED;
                    $inquiry->update(false);
                } elseif(Yii::$app->request->post('status_id') == Payment::OFFER_REFUNDED) {
                    $inquiry = Inquiry::findOne([Yii::$app->request->post('inquiry_id')]);
                    $inquiry->is_viewed_by_admin = Inquiry::IS_NOT_VIEWED;
                    $inquiry->update(false);
                }

                $payment->offer_status = (int)Yii::$app->request->post('status_id');
                $payment->update(false);
            }
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionSummaryInvoice()
    {
        $payment_list = Payment::find()
            ->where(['invoice_status' => Payment::INVOICE_NOT_SENT])
            ->andWhere(['status' => Payment::STATUS_SUCCEEDED])
            ->andWhere(['offer_status' => Payment::OFFER_COMPLETED])
            ->all();

        if (!empty($payment_list)) {

            $payment_id_list = ArrayHelper::map($payment_list, 'id', 'id');
            $invoices = new SummaryInvoice($payment_list);
            $generation = new InvoiceGeneration();
            $generation->created_at = time();
            $generation->save();
            $generation->refresh();

            foreach ($invoices->invoice_items as $user_id => $invoice_item) {
                $this->summaryInvoiceGenerate($invoice_item, $user_id, $generation->id);
            }

            Payment::updateAll(['invoice_status' => Payment::INVOICE_SENT], ['id' => $payment_id_list]);

            Yii::$app->session->setFlash('alert', [
                'options' => ['class' => 'alert-success'],
                'body' => Yii::t('app', 'Invoices sent')
            ]);

            return $this->redirect(Yii::$app->request->referrer);

        } else {
            Yii::$app->session->setFlash('alert', [
                'options' => ['class' => 'alert-danger'],
                'body' => Yii::t('app', 'Invoices for existing payments are already sent')
            ]);
            return $this->redirect(Yii::$app->request->referrer);
        }
    }

    private function summaryInvoiceGenerate($invoice, $user_id, $generation_date_id)
    {
        if (!is_dir(Yii::getAlias('@storage/web/source/invoice'))) {
            mkdir(Yii::getAlias('@storage/web/source/invoice'));
        }

        $pdf =
            [
                'content' => $this->renderPartial('pdf/summary-invoice', ['invoice' => $invoice]),
                'filename' => Yii::$app->security->generateRandomString(16) . '.pdf',
            ];

        $create_pdf = new Pdf([
            'mode' => Pdf::MODE_CORE,
            'filename'=> Yii::getAlias('@storage/web/source/invoice/') . $pdf['filename'],
            'format' => Pdf::FORMAT_A4,
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'destination' => Pdf::DEST_FILE,
            'content' => $pdf['content'],
        ]);

        $create_pdf->render();
        $document = new \common\models\Invoice();
        return $document->savePdf($pdf, $user_id, $generation_date_id, $invoice['net_total']);
    }

    public function actionInvoice()
    {
        $payment_list = Payment::find()
            ->where(['invoice_status' => Payment::INVOICE_NOT_SENT])
            ->andWhere(['status' => Payment::STATUS_SUCCEEDED])
            ->andWhere(['offer_status' => Payment::OFFER_COMPLETED])
            ->groupBy(['doctor_id'])
            ->all();

        if (!empty($payment_list)) {
            $payment_id_list = ArrayHelper::map($payment_list, 'id', 'id');
            Payment::updateAll(['invoice_status' => Payment::INVOICE_SENT], ['id' => $payment_id_list]);

            foreach ($payment_list as $payment) {
                $invoice = new Invoice($payment, true);
                $this->invoiceGenerate($invoice, $payment->doctor_id);
            }

            Yii::$app->session->setFlash('alert', [
                'options' => ['class' => 'alert-success'],
                'body' => Yii::t('app', 'Invoices sent')
            ]);

            return $this->redirect(Yii::$app->request->referrer);

        } else {
            Yii::$app->session->setFlash('alert', [
                'options' => ['class' => 'alert-danger'],
                'body' => Yii::t('app', 'Invoices for existing payments are already sent')
            ]);
            return $this->redirect(Yii::$app->request->referrer);
        }
    }

    private function invoiceGenerate($invoice, $user_id)
    {
        if (!is_dir(Yii::getAlias('@storage/web/source/invoice'))) {
            mkdir(Yii::getAlias('@storage/web/source/invoice'));
        }

        $pdf =
            [
                'content' => $this->renderPartial('pdf/invoice', ['invoice' => $invoice]),
                'filename' => Yii::$app->security->generateRandomString(16) . '.pdf',
            ];

            $create_pdf = new Pdf([
                'mode' => Pdf::MODE_CORE,
                'filename'=> Yii::getAlias('@storage/web/source/invoice/') . $pdf['filename'],
                'format' => Pdf::FORMAT_A4,
                'orientation' => Pdf::ORIENT_PORTRAIT,
                'destination' => Pdf::DEST_FILE,
                'content' => $pdf['content'],
            ]);

            $create_pdf->render();
            $document = new \common\models\Invoice();
            return $document->savePdf($pdf, $user_id);
    }

    /**
     * Finds the Payment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Payment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Payment::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
