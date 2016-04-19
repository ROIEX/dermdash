<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;


/* @var $this yii\web\View */
/* @var $model common\models\TreatmentItem */

$this->title = Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Treatment category',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Treatment Items'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="treatment-category-create">

    <div class="treatment-category-form">

        <?php $form = ActiveForm::begin(); ?>

        <?php echo $form->errorSummary($model); ?>

        <?php echo $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

        <div class="form-group">
            <?php echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>
