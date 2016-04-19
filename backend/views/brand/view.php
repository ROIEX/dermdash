<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\Brand;
use common\models\Treatment;
use common\components\dateformatter\FormatDate;
use common\components\StatusHelper;

/* @var $this yii\web\View */
/* @var $model common\models\Brand */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Brands'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="brand-view">

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
            'name',
            [
                'label' => Yii::t('app', 'Photo'),
                'value' => Html::img(Brand::getPhoto($model->icon_base_url, $model->icon_path), ['width' => 75, 'height' => 75]),
                'format' => 'raw',
            ],
            'sub_string:ntext',
            'instruction:ntext',
            [
                'attribute' => 'treatment_id',
                'value' => Treatment::getTreatmentName($model->treatment_id),
            ],
            [
                'attribute' => 'created_at',
                'value' => FormatDate::AmericanFormatFromTimestamp($model->created_at),
            ],
            [
                'attribute' => 'status',
                'value' => StatusHelper::getStatus($model->status),
            ],
        ],
    ]) ?>

</div>
