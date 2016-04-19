<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Treatment */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Treatment',
]) . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Treatments'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="treatment-update">

    <?php echo $this->render('_form', [
        'model' => $model,
        'param_models' => $param_models,
        'session_models' => $session_models,
        'severity_models' => $severity_models,
        'intensity_models' => $intensity_models,
    ]) ?>

</div>
