<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Doctor */

$this->title = Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Doctor',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Doctors'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container">
    <?php echo $this->render('_form', [
        'model' => $model,
        'brands' => $brands,
        'treatments' => $treatments,
    ]) ?>
</div>

