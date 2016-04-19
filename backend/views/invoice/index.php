<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Generation Dates');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="invoice-index">

    <?php echo GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'created_at',
                'format' => 'raw',
                'value' => function($data) {
                    return Html::a( \common\components\dateformatter\FormatDate::AmericanFormatFromTimestamp($data->created_at, true), Url::toRoute(['invoice-item/index', 'id' => $data->id]))   ;
                }
            ],
        ],
    ]); ?>

</div>
