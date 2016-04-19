<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Severity */

$this->title = Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Severity',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Severities'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="severity-create">

    <?php echo $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
