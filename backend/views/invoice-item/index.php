<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Invoices');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="invoice-index">

    <?php echo GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'Clinic',
                'format' => 'raw',
                'value' => function($data) {
                    if (Yii::$app->user->can('administrator')) {
                        return Html::a($data->user->doctor->clinic, Url::toRoute(['doctor/view', 'id' => $data->user->doctor->id]));
                    } else {
                        return $data->user->doctor->clinic;
                    }

                }
            ],
            [
                'label' => Yii::t('app', 'Document'),
                'format' => 'raw',
                'value' => function($data){
                    return Html::a(Yii::t('app', 'Document'),$data->file_path, ['target' => 'blank']);
                }
            ],
            [
                'label' => Yii::t('app', 'Net Total')
            ]
        ],
    ]); ?>

</div>
