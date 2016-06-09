<?php
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Doctor */
/* @var $form yii\bootstrap\ActiveForm */

$this->registerCssFile(Yii::getAlias('@backendUrl' . '/css/doctor-create.css'));
$this->registerCssFile(Yii::getAlias('https://fonts.googleapis.com/css?family=Muli:300'));
?>
<div class="header">

    <?php echo $this->render('registration/_form-header') ?>


</div>

<div class="content">
    <div class="form-section">

        <?php $form = ActiveForm::begin([
            'enableClientValidation' => true,
            'enableAjaxValidation' => true
        ]); ?>

        <?php echo $this->render('registration/_form-doctor-fields', ['model' => $model, 'form' => $form]) ?>

        <?php echo $this->render('registration/_form-footer', ['model' => $model, 'form' => $form]) ?>

        <?php ActiveForm::end(); ?>

    </div>
