<?php

use common\components\dateformatter\FormatDate;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Payment */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Payments'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="payment-view">

    <p>
        <?php echo Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?php echo DetailView::widget([
        'model' => $model,
        'attributes' => [

            [
                'attribute' => 'user_id',
                'label' => Yii::t('app', 'Patient email'),
                'value' => $model->user->email
            ],
            'payment_id',
            [
                'attribute' => 'crated_at',
                'label' => Yii::t('app', 'Payment date'),
                'value' => FormatDate::AmericanFormatFromTimestamp($model->created_at),
            ],
            'status',
            [
                'attribute' => 'amount',
                'value' => $model->amount / 100 . ' $'
            ],
        ],
    ]) ?>



<!---->
<!--    --><?php //if ($inquiry) { ?>
<!--        <div><h3>--><?php //echo Yii::t('app', 'Doctor: ') ?><!--</h3></div>-->
<!--        --><?php //echo $model->paymentItems[0]->doctorList->user->email ?>
<!--        <div><h3>--><?php //echo Yii::t('app', 'Item Description: ') ?><!--</h3></div>-->
<!--        --><?php //echo $inquiry->getCureType($inquiry->type) . ': ' ?>
<!--    --><?php //} ?>
<!--    --><?php //if ($invoice) { ?>
<!--        --><?php //echo $invoice->item_description ?>
<!--        <div><h3>--><?php //echo Yii::t('app', 'Total Price: ') ?><!--</h3></div>-->
<!--        --><?php //echo $invoice->total_price / 100 . ' $'?>
<!--        <div><h3>--><?php //echo Yii::t('app', 'Fees: ') ?><!--</h3></div>-->
<!--        --><?php //echo $invoice->fee . ' %'?>
<!--        <div><h3>--><?php //echo Yii::t('app', 'Net Total: ') ?><!--</h3></div>-->
<!--        --><?php //echo $invoice->net_total / 100 . ' $'?>
<!--    --><?php //} ?>






</div>
