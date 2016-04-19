<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\PromoCode */

$this->title = Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Promo Code',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Promo Codes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="promo-code-create">

    <?php echo $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
