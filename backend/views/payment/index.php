<?php

use common\components\dateformatter\FormatDate;
use common\models\Payment;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Payments');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="payment-index">

    <p>
        <?php echo Html::a(Yii::t('app', 'Send invoices'), ['summary-invoice'], [
            'class' => 'btn btn-success',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to send invoices?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?php echo GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'user_id',
                'format' => 'raw',
                'label' => Yii::t('app', 'Patient email'),
                'value' => function($data) {
                    return Html::a($data->user->email, Url::toRoute(['patient/view', 'id' => $data->user_id]));
                }
            ],
            [
                'attribute' => 'doctor_id',
                'format' => 'raw',
                'label' => Yii::t('app', 'Clinic'),
                'value' => function($data) {
                    return Html::a($data->doctor->doctor->clinic, Url::toRoute(['doctor/view', 'id' => $data->doctor->doctor->id]));
                }
            ],
            [
                'attribute' => 'inquiry_id',
                'format' => 'raw',
                'label' => Yii::t('app', 'Invoice #'),
                'value' => function($data) {
                    return Html::a($data->inquiry_id, Url::toRoute(['inquiry/view', 'note_id' => $data->inquiry_id, 'doctor_id' => $data->doctor_id]));
                }
            ],
            [
                'attribute' => 'created_at',
                'label' => Yii::t('app', 'Date'),
                'value' => function($data) {
                    return FormatDate::AmericanFormatFromTimestamp($data->created_at);
                }
            ],

            'status',
            [
                'attribute' => 'offer_status',
                'value' => function($data) {
                    return Payment::getOfferStatus($data->offer_status);
                }
            ],
            [
                'attribute' => 'amount',
                'label' => Yii::t('app', 'Amount') . ', $',
                'value' => function($data) {
                    return $data->amount / 100;
                }
            ],

            ['class' => 'yii\grid\ActionColumn', 'template' => '{view}'],
        ],
    ]); ?>

</div>
