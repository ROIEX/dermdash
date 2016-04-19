<?php

use fedemotta\datatables\DataTables;
use yii\helpers\Html;
use yii\helpers\Url;
use common\models\UserProfile;
use common\components\dateformatter\FormatDate;

$this->title = Yii::t('app', 'Patients');
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="patient-index">

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
                'attribute' =>  'firstname',
                'label'=> Yii::t('app', 'Patient name'),
                'format' => 'raw',
                'value' => function($data) {
                    return Html::a($data->getPublicIdentity(), Url::toRoute(['patient/view', 'id' => $data->id]));
                }
            ],
            [
                'label'=> Yii::t('app', 'Email'),
                'value' => function($data) {
                    return $data->email;
                }
            ],
            [
                'label'=> Yii::t('app', 'City'),
                'value' => function($data) {
                    return isset($data->userProfile->city) ? $data->userProfile->city : '';
                }
            ],
            [
                'label'=> Yii::t('app', 'State'),
                'value' => function($data) {
                    return isset($data->userProfile->state->name) ? $data->userProfile->state->name : '';
                }
            ],
            [
                'label'=> Yii::t('app', 'Gender'),
                'value' => function($data) {
                    return isset($data->userProfile->gender) ? UserProfile::getGender($data->userProfile->gender) : '';
                }
            ],
            [
                'label'=> Yii::t('app', 'Registration date'),
                'value' => function($data) {
                    return FormatDate::AmericanFormatFromTimestamp($data->created_at);
                }
            ],
        ],
    ]); ?>

</div>
