<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'User list');
$this->params['breadcrumbs'][] = ['label' => 'Promo list', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="promo-code-index">

    <?php echo \yii\grid\GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'label' => 'Patient',
                'value' => function($data){
                    return $data->user->email;
                }
            ],
            [
                'label' => Yii::t('app', 'Patient'),
                'format' => 'raw',
                'value' => function($data){
                    return Html::a(Yii::t('app', 'View profile'), Url::toRoute(['patient/view', 'id' => $data->user->id]));
                }
            ],

        ],
    ]); ?>

</div>