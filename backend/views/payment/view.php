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

</div>
