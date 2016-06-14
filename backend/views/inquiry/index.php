<?php

use common\models\Payment;
use yii\helpers\Html;
use yii\helpers\Url;
use common\components\dateformatter\FormatDate;
use fedemotta\datatables\DataTables;
use yii\widgets\Pjax;

$url = Url::toRoute('/payment/payment-status');
$js = <<<SCRP

$(document).on('change', "[name=offer_status]", function(event){
    updateOfferStatus('{$url}', $(this).data('inquiry_id'), $(this).val());
    return false;
})
SCRP;
$this->registerJs($js);
?>
<?php Pjax::begin(['id' => 'inquiry_list']); ?>
    <?php echo DataTables::widget([
    'dataProvider' => $dataProvider,
    'clientOptions' => [
        "lengthMenu"=> [[20,-1], [20,Yii::t('app',"All")]],
        "info" => false,
        "responsive" => true,
    ],
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],

        [
            'label' => Yii::t('app', 'Invoice #'),
            'format' => 'raw',
            'value' => function($data) use($completed_page){
                if ($completed_page) {
                    return Html::a($data->id, Url::toRoute(['inquiry/view', 'note_id' => $data->id, 'doctor_id' =>  $data->doctorAccepted->user_id]));
                } else {
                    return $data->id;
                }
            }
        ],
        [
            'label' => Yii::t('app', 'Patient'),
            'format' => 'raw',
            'value' => function ($data) {
                if ($data->user->id == \common\models\User::GUEST_ACCOUNT_ID) {
                    return 'guest';
                } else {
                    return Html::a($data->user->email, Url::toRoute(['patient/view', 'id' => $data->user->id]));
                }

            },
            'visible' => Yii::$app->user->can('administrator'),
        ],
        [
            'label' => Yii::t('app', 'Clinic'),
            'format' => 'raw',
            'value' => function ($data) {
                return Html::a($data->doctorAccepted->user->doctor->clinic, Url::toRoute(['doctor/view', 'id' => $data->doctorAccepted->user->doctor->id]));
            },
            'visible' => Yii::$app->user->can('administrator') && $completed_page,
        ],
        [
            'label' => Yii::t('app', 'Date'),
            'value' => function($data) {
                return FormatDate::AmericanFormatFromTimestamp($data->created_at);
            }
        ],

        [
            'label' => Yii::t('app', 'Items Viewed'),
            'value' => function($data) {
                return $data->getInquiryItem();
            }
        ],
        [
            'label' => Yii::t('app', 'Visit Status'),
            'format' => 'raw',
            'value' => function($data) {
                if (Yii::$app->user->can('administrator')) {
                    $status_list = Payment::getAdminOfferStatusArray();
                } else {
                    $status_list = Payment::getDoctorOfferStatusArray();
                }
                if (!Yii::$app->user->can('administrator')) {
                    if ($data->payment->offer_status == Payment::OFFER_COMPLETED) {
                        unset($status_list[Payment::OFFER_PENDING]);
                    }
                }
                return Html::dropDownList('offer_status', $data->payment->offer_status, $status_list,
                    [
                        'options' => [
                            Payment::OFFER_REFUND_REQUESTED => ['disabled' => Yii::$app->user->can('administrator') ? true : false],
                            Payment::OFFER_REFUNDED => ['disabled' => !Yii::$app->user->can('administrator') ? true : false],
                        ],
                        'data' => ['inquiry_id' => $data->id],
                    ]
                );
            },
            'visible' => $completed_page ? true : false,

        ],
    ]
]); ?>
<?php Pjax::end(); ?>
