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
                    if (Yii::$app->user->can('administrator')) {
                        return $model->inquiry->id;
                    } else {
                        return Html::a($model->inquiry->id, Url::toRoute(['inquiry/view', 'note_id' => $model->inquiry->id, 'doctor_id' =>  Yii::$app->user->id]));
                    }

                }
            ],
            [
                'attribute' => 'created_at',
                'value' => function($model) {
                    return \common\components\dateformatter\FormatDate::AmericanFormatFromTimestamp($model->created_at);
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
                'value' => function($model) {
                    if ($model->inquiry->user->id == \common\models\User::GUEST_ACCOUNT_ID || !Yii::$app->user->can('administrator')) {
                        return $model->email;
                    } else {
                        return Html::a($model->email, Url::toRoute(['patient/view', 'id' => $model->inquiry->user->id]));
                    }

                },
            ],
            'first_name',
            'last_name',
            'phone_number',
            [
                'label' => Yii::t('backend', 'Clinic Name'),
                'format' => 'raw',
                'value' => function($model) {
                    $doctor = $model->inquiry->getBookedDoctor($model->inquiry_id);
                    return Html::a($doctor->clinic, Url::toRoute(['/doctor/view', 'id' => $doctor->id]));
                },
                'visible' => Yii::$app->user->can('administrator')

            ],
            [
                'label' => Yii::t('backend', 'Reason'),
                'value' => function($model) {
                    return $model->inquiry->getInquiryItem();
                }
            ],
        ],
    ]); ?>

</div>
