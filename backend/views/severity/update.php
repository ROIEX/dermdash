<?php

/* @var $this yii\web\View */
/* @var $model common\models\Severity */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Severity',
]) . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Settings'), 'url' => ['/setting/index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="severity-update">

    <?php echo $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
