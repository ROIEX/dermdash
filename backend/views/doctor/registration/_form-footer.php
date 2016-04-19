<?php

use yii\helpers\Html;

?>

<div class="form-section">

    <div class="checkbox">
        <?php echo $form->field($model, 'terms')->checkbox()
            ->label(Yii::t('app', 'By checking "accept" i have read and agree to the ' ) .
                Html::a(Yii::t('app', 'Terms of Service'), 'http://www.dermdash.com/terms-of-service', ['target' => '_blank']) . ", " .
                Html::a(Yii::t('app', 'Privacy Policy'), 'http://www.dermdash.com/dermdash-privacy-policy', ['target' => '_blank']) . ", " .
                Html::a(Yii::t('app', 'Service Agreement'), Yii::getAlias('@storageUrl') . '/pdf/service_agreement.pdf', ['target' => '_blank']) . " and " .
                Html::a(Yii::t('app', 'HIPAA BUSINESS ASSOCIATE AGREEMENT'), Yii::getAlias('@storageUrl') . '/pdf/business_associate.pdf', ['target' => '_blank'])
            ); ?>
    </div>
</div>

<div class="form-section">
    <div class="row">
        <div class="col-sm-8">
            <div class="form-group">
                <?php echo $form->field($model, 'signature')->textInput(['placeholder' => Yii::t('app', 'Enter name of the person filling out this form')]) ?>
            </div>
        </div>
    </div>
</div>

<div class="form-group">
    <?php echo Html::submitButton(Yii::t('app', 'Submit'), ['class' => 'submit-button btn btn-default center-block']) ?>
</div>
