<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Intensity */

$this->title = Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Intensity',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Intensities'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="intensity-create">

    <?php echo $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
