<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\BookingSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'Bookings');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="booking-index">

    <?php echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' =>  'inquiry_id',
            ],
            [
                'attribute' => 'date',
                'value' => function($model) {
                    return \common\components\dateformatter\FormatDate::AmericanFormat($model->date);
                }
            ],
            'email:email',
            'first_name',
            'last_name',
            'phone_number',
            [
                'label' => Yii::t('backend', 'Reason'),
                'value' => function($model) {
                    return $model->inquiry->getInquiryItem();
                }
            ],
            ['class' => 'yii\grid\ActionColumn', 'template' => '{view} {delete}'],
        ],
    ]); ?>

</div>
