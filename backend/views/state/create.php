<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\State */

$this->title = Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'State',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'States'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="state-create">

    <?php echo $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
