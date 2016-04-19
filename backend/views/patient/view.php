<?php

/* @var $this yii\web\View */
    /* @var $model common\models\Doctor */

$this->title = $model->getPublicIdentity();
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Patients'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="doctor-view">

    <div class="row">
        <div class="col-xs-12">
            <?php echo  $this->render('basic_info', ['model' => $model, 'used_promo' => $used_promo]); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <?php echo  $this->render('chart-note-list', ['model' => $inquiry_list]); ?>
        </div>
    </div>
</div>
