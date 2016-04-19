<?php
/* @var $this \yii\web\View */
/* @var $model \common\models\MailchimpList */


$form = \yii\widgets\ActiveForm::begin();

?>
    <?= $form->field($model,'name'); ?>

    <h1><?= Yii::t('app','Contact') ?></h1>
    <?= $form->field($model,'company'); ?>
    <?= $form->field($model,'address1'); ?>
    <?= $form->field($model,'address2'); ?>
    <?= $form->field($model,'city'); ?>
    <?= $form->field($model,'state'); ?>
    <?= $form->field($model,'zip'); ?>
    <?= $form->field($model,'country'); ?>
    <?= $form->field($model,'phone'); ?>

    <h1><?= Yii::t('app','Campaign Defaults') ?></h1>
    <?= $form->field($model,'from_name'); ?>
    <?= $form->field($model,'from_email'); ?>
    <?= $form->field($model,'subject'); ?>
    <?= $form->field($model,'language'); ?> <br>
    <?= $form->field($model,'notify_on_subscribe'); ?>
    <?= $form->field($model,'notify_on_unsubscribe'); ?>
    <?= $form->field($model,'permission_reminder'); ?>
    <?= $form->field($model,'visibility')->dropDownList($model->getVisibility()); ?>
    <?= \yii\helpers\Html::submitButton(Yii::t('backend','Save'),['class'=>'btn btn-success']) ?>
<?php \yii\widgets\ActiveForm::end(); ?>