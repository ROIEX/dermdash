<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\BodyPart */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Body Part',
]) . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Body Parts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="body-part-update">

    <?php echo $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
