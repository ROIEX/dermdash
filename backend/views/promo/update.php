<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\PromoCode */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Promo Code',
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Promo Codes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="promo-code-update">

    <?php echo $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
