<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
use common\models\User;

/* @var $this yii\web\View */
/* @var $model common\models\PromoCode */
/* @var $form yii\bootstrap\ActiveForm */
?>

<div class="promo-code-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php echo $form->errorSummary($model); ?>

<!--    --><?php //echo $form->field($model, 'user_id')->dropDownList(ArrayHelper::map(User::find()->patientList()->all(), 'id', 'email'), ['prompt' => Yii::t('app', 'Assign to user')])->label(Yii::t('app', 'Email')); ?>

    <?php echo $form->field($model, 'text')->textInput(['maxlength' => true]) ?>

    <?php echo $form->field($model, 'value')->textInput(['maxlength' => true]) ?>

    <?php echo $form->field($model, 'is_reusable')->checkbox() ?>

    <?php echo $form->field($model, 'description')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?php echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
