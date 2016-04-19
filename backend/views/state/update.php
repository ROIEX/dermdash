<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\State */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'State',
]) . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'States'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="state-update">

    <?php echo $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
