<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\components\dateformatter\FormatDate;
use common\components\StatusHelper;
use common\models\Treatment;

/* @var $this yii\web\View */
/* @var $model common\models\Treatment */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Treatments'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="treatment-view">

    <p>

       <?php if ($model->treatmentParams[0]->brandProvided) {
            $link = 'update-brand-provided';
        } else {
            $link = 'update';
        } ?>
       <?php echo Html::a(Yii::t('app', 'Update'), [$link, 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
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
                'value' => Html::img(Treatment::getPhoto($model->icon_base_url, $model->icon_path), ['width' => 75, 'height' => 75]),
                'format' => 'raw',
            ],
            'sub_string:ntext',
            [
                'attribute' => 'created_at',
                'value' => FormatDate::AmericanFormatFromTimestamp($model->created_at),
            ],
            [
                'attribute' => 'status',
                'value' => StatusHelper::getStatus($model->status),
            ],
            'param_multiselect',
            'body_part_multiselect',
        ],
    ]) ?>

</div>
