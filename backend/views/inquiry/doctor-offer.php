<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

?>

<?php $form = ActiveForm::begin(['action' => Url::toRoute(['/inquiry/create-offer', 'id' => $model->id])]); ?>

    <?php echo $form->errorSummary($model); ?>

    <?php echo $form->field($model, 'price')->textInput(['maxlength' => true])?>

    <?php echo $form->field($model, 'comment')->textarea(['maxlength' => true])?>

    <?php echo Html::submitButton(Yii::t('app', 'Create'), ['class' => 'btn btn-success']) ?>

<?php ActiveForm::end(); ?>
