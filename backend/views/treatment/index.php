<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\components\StatusHelper;
use common\models\Treatment;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Treatments');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="treatment-index">


    <div class="row">

        <?php echo Html::a(Yii::t('app', 'Create {modelClass}', [
            'modelClass' => 'Treatment',
        ]), ['create'], ['class' => 'btn btn-success']) ?>

        <?php echo Html::a(Yii::t('app', 'Create brand provided treatment', [
            'modelClass' => 'Treatment',
        ]), ['create-brand-provided'], ['class' => 'btn btn-success']) ?>

    </div>

    <?php echo GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'status',
                'label' => Yii::t('app', 'Status'),
                'value' => function($data){
                    return StatusHelper::getStatus($data->status);
                }
            ],
            'name',
            [
                'label' => Yii::t('app', 'Photo'),
                'format' => 'raw',
                'value' => function($data){
                    return Html::img(Treatment::getPhoto($data->icon_base_url, $data->icon_path), ['width' => 75, 'height' => 75]);
                }
            ],
            'sub_string:ntext',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update}',
                'buttons' => [
                    'update' => function($url,$model,$key){
                        if ($model->treatmentParams[0]->brandProvided) {
                            $url = \yii\helpers\Url::to(['update-brand-provided', 'id' => $key]);
                        }
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                            'title' => Yii::t('app', 'View'),
                        ]);
                    }
                ]
            ],
        ],
    ]); ?>

</div>
