<?php

namespace console\controllers;

use common\components\SummaryInvoice;
use common\models\Invoice;
use common\models\InvoiceGeneration;
use common\models\Payment;
use kartik\mpdf\Pdf;
use Yii;
use yii\console\Controller;
use yii\helpers\ArrayHelper;
use yii\helpers\Console;

class InvoiceController extends Controller
{
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

            return Console::stdout('Success');

        } else {
            return Console::stdout('Invoices for existing payments are already sent') ;
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
        $document = new Invoice();
        return $document->savePdf($pdf, $user_id, $generation_date_id, $invoice['net_total']);
    }
}