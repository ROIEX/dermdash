<?php

use yii\grid\GridView;
use yii\helpers\Html;
use common\models\Doctor;
use common\models\UserProfile;
use common\models\DoctorBrand;
use common\models\DoctorTreatment;
use common\components\dateformatter\FormatDate;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Doctor */

$this->title = $model->profile->getFullName();
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Doctors'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="doctor-view">

    <p>
        <?php echo Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
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
                'label'=>'Name',
                'value' => $model->profile->getFullName()
            ],
            [
                'label' => Yii::t('app', 'Photo'),
                'value' => Html::img(Doctor::getPhoto($model->profile->avatar_base_url, $model->profile->avatar_path), ['width' => 75, 'height' => 75]),
                'format'=> 'raw',
            ],
            [
                'label' => Yii::t('app', 'Email'),
                'value' => $model->user->email,
            ],
            [
                'label'=> Yii::t('app', 'Office phone'),
                'value' => $model->profile->phone,
            ],
            [
                'attribute' => 'gender',
                'value' =>  UserProfile::getGender($model->profile->gender),
            ],
            [
                'attribute' => 'doctor_type',
                'value' => Doctor::getDoctorType($model->doctor_type),
            ],
            [
                'attribute' => 'date_of_birth',
                'value' => FormatDate::AmericanFormat($model->profile->date_of_birth),
            ],
            'license',
            'clinic',
            [
                'label' => Yii::t('app', 'Selected treatments'),
                'value' => DoctorTreatment::getSelectedList($model->user_id),
            ],
            [
                'label' => Yii::t('app', 'Selected brands'),
                'value' => DoctorBrand::getSelectedList($model->user_id),
            ]
        ],
    ]); ?>

    <h3><?php echo Yii::t('app', 'Payment history')?></h3>

    <?php echo GridView::widget([
        'dataProvider' => $payment_model,
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
            'payment_id',
            [
                'attribute' => 'created_at',
                'label' => Yii::t('app', 'Date'),
                'value' => function($data) {
                    return FormatDate::AmericanFormatFromTimestamp($data->created_at);
                }
            ],

            'status',
            [
                'attribute' => 'amount',
                'label' => Yii::t('app', 'Amount') . ', $',
                'value' => function($data) {
                    return $data->amount / 100;
                }
            ],

            ['class' => 'yii\grid\ActionColumn', 'template' => '{view}', 'controller' => 'payment',],
        ],
    ]); ?>

</div>
