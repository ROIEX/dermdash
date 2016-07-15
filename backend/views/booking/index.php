<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

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
                'format' => 'raw',
                'value' => function($model) {
                    return Html::a($model->inquiry->id, Url::toRoute(['inquiry/view', 'note_id' => $model->inquiry->id, 'doctor_id' =>  $model->inquiry->doctorAccepted->user_id]));
                }
            ],
            [
                'attribute' => 'date',
                'value' => function($model) {
                    return \common\components\dateformatter\FormatDate::AmericanFormat($model->date);
                }
            ],
            [
                'label' => Yii::t('app', 'Email'),
                'format' => 'raw',
                'value' => function ($model) {
                    if ($model->inquiry->user->id == \common\models\User::GUEST_ACCOUNT_ID) {
                        return 'guest';
                    } else {
                        return Html::a($model->inquiry->user->email, Url::toRoute(['patient/view', 'id' => $model->inquiry->user->id]));
                    }

                },
                'visible' => Yii::$app->user->can('administrator'),
            ],
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
