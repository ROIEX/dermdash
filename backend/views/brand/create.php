<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Brand */

$this->title = Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Brand',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Brands'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="brand-create">

    <?php echo $this->render('_form', [
        'model' => $model,
        'param_models' => $param_models,
    ]) ?>

</div>
