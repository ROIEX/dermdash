<?php

use yii\helpers\Html;
use yii\helpers\Url;
use fedemotta\datatables\DataTables;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Promo Codes');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="promo-code-index">


    <p>
        <?php echo Html::a(Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Promo Code',
]), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php echo DataTables::widget([
        'dataProvider' => $dataProvider,
        'clientOptions' => [
            "lengthMenu"=> [[20,-1], [20,Yii::t('app',"All")]],
            "info"=>false,
            "responsive"=>true,
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'user_id',
                'label' => Yii::t('app', 'Patient email'),
                'value' => function($data) {
                    return isset($data->user) ? $data->user->email : Yii::t('app', 'Is shared');
                }
            ],
            'text',
            'value',
            [
                'attribute' => 'is_reusable',
                'value' => function($data) {
                    return \common\models\PromoCode::getType($data->is_reusable);
                }
            ],
             'description',
            [
                'label' => Yii::t('app', 'Registration Counter'),
                'format' => 'raw',
                'value' => function($data) {
                    if ($data->usedCountRegistration > 0) {
                        return Html::a($data->usedCountRegistration, Url::toRoute(['promo/statistic', 'id' => $data->id, 'used_while' => \common\models\PromoUsed::USED_WHILE_REGISTRATION]));
                    }
                    return $data->usedCountRegistration;

                }
            ],
            [
                'label' => Yii::t('app', 'Purchase Counter'),
                'format' => 'raw',
                'value' => function($data) {
                    if ($data->usedCountPurchase > 0) {
                        return Html::a($data->usedCountPurchase, Url::toRoute(['promo/statistic', 'id' => $data->id, 'used_while' => \common\models\PromoUsed::USED_WHILE_PURCHASE]));
                    }
                    return $data->usedCountPurchase;

                }
            ],
            ['class' => 'yii\grid\ActionColumn', 'template' => '{view} {delete}'],
        ],
    ]); ?>

</div>
