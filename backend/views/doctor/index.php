<?php

use yii\helpers\Html;
use yii\helpers\Url;
use fedemotta\datatables\DataTables;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Doctors');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="doctor-index">

    <?php echo DataTables::widget([
        'dataProvider' => $dataProvider,
        'clientOptions' => [
            "lengthMenu"=> [[20,-1], [20,Yii::t('app',"All")]],
            "info" => false,
            "responsive" => true,
        ],
        'columns' => [
            [
                'attribute' => 'status',
                'label'=> 'Status',
                'value' =>function($data){
                    return common\components\StatusHelper::getStatus($data->status);
                }
            ],
            [
                'label'=> Yii::t('app', 'Photo'),
                'format' => 'raw',
                'value' => function($data){
                    return Html::img(\common\models\Doctor::getPhoto($data->profile->avatar_base_url, $data->profile->avatar_path), ['width' => 75, 'height' => 75]);
                }
            ],
            [
                'attribute' => 'first_name',
                'label'=> Yii::t('app', 'Doctor name'),
                'format' => 'raw',
                'value' => function($data) {
                    $profile = $data->profile;
                    return Html::a($profile->firstname . " " . $profile->lastname, Url::toRoute(['doctor/view','id' => $data->id]));
                }
            ],
            [
                'attribute' => 'email',
                'value' => function($data) {
                    return $data->user->email;
                }
            ],
            'clinic',
            'license',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
