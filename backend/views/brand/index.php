<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Brand;
use common\components\StatusHelper;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Brands');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="brand-index">


    <p>
        <?php echo Html::a(Yii::t('app', 'Create {modelClass}', [
            'modelClass' => 'Brand',
        ]), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

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
                    return Html::img(Brand::getPhoto($data->icon_base_url, $data->icon_path), ['width' => 75, 'height' => 75]);
                }
            ],
            'sub_string:ntext',
            'instruction:ntext',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
