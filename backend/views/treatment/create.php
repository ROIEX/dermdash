<?php

/* @var $this yii\web\View */
/* @var $model common\models\Treatment */

$this->title = Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Treatment',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Treatments'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="treatment-create">

    <?php echo $this->render('_form', [
        'model' => $model,
        'param_models' => $param_models,
        'session_models' => $session_models,
        'severity_models' => $severity_models,
        'intensity_models' => $intensity_models,
    ]) ?>

</div>
