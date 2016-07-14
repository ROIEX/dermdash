<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Booking */

$this->title = Yii::t('backend', 'Create {modelClass}', [
    'modelClass' => 'Booking',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Bookings'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="booking-create">

    <?php echo $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
