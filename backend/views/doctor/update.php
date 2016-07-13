<?php

/* @var $this yii\web\View */
/* @var $model common\models\Doctor */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Doctor',
]) . ' ' . $model->getModel('user_profile')->getFullName();

if (Yii::$app->user->can('administrator')) {
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Doctors'), 'url' => ['index']];
    $this->params['breadcrumbs'][] = ['label' => $model->getModel('user_profile')->getFullName(), 'url' => ['view', 'id' => $model->getModel('doctor_model')->id]];
    $this->params['breadcrumbs'][] = Yii::t('app', 'Update');
}


?>
<div class="doctor-update">
   
    <?php echo $this->render('update_form', [
        'model' => $model,
        'brands' => $brands,
        'treatments' => $treatments,
        'selected_treatments' => $selected_treatments,
        'selected_brands' => $selected_brands,
        'treatment_special' => $treatment_special,
        'brand_special' => $brand_special,
        'dropdown_special' => $dropdown_special,
        'selected_brands_dropdown_prices' => $selected_brands_dropdown_prices,
    ]) ?>

</div>
