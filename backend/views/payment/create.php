<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Payment */

$this->title = Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Payment',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Payments'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="payment-create">

    <?php echo $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
