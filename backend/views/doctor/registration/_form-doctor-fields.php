<?php

use common\models\Doctor;
use yii\widgets\MaskedInput;
use yii\web\JsExpression;
use common\models\UserProfile;
use common\models\State;

?>

<?php echo $form->errorSummary($model); ?>

<h3><span><?php echo Yii::t('app', 'Doctor information') ?></span></h3>

<div class="row">
    <div class="col-sm-6">
        <?php echo $form->field($model, 'firstname')->textInput(['maxlength' => true, 'placeholder' => Yii::t('app', 'Enter First Name')]) ?>
    </div>

    <div class="col-sm-6">
        <?php echo $form->field($model, 'lastname')->textInput(['maxlength' => true, 'placeholder' => Yii::t('app', 'Enter Last Name')]) ?>
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        <?php echo $form->field($model, 'email')->textInput(['maxlength' => true, 'placeholder' => Yii::t('app', 'Enter Email')]) ?>
    </div>

    <div class="col-sm-6">
        <?php echo $form->field($model, 'date_of_birth')->widget(MaskedInput::className(), [
            'clientOptions' => ['alias' => 'mm/dd/yyyy', 'placeholder' => Yii::t('common', 'MM/DD/YYYY')]
        ]) ?>
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        <?php echo $form->field($model, 'password')->passwordInput(['maxlength' => true, 'placeholder' => '******']) ?>
    </div>

    <div class="col-sm-6">
        <?php echo $form->field($model, 'password_confirm')->passwordInput(['maxlength' => true, 'placeholder' => '******']) ?>
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        <?php echo $form->field($model, 'uploaded_image')->widget('\trntv\filekit\widget\Upload', [
            'url' => ['/file-storage/upload'],
            'sortable' => true,
            'acceptFileTypes' => new JsExpression('/(\.|\/)(jpe?g|png)$/i'),
            'maxFileSize' => 10 * 1024 * 1024, // 10 MiB,
        ])->label(Yii::t('app', 'Add Photo')); ?>

        <?php echo \yii\helpers\Html::activeHint($model, 'uploaded_image', [ 'hint' => Yii::t('app', 'Image size must be less than 10 Mb')])?>
    </div>

    <div class="col-sm-6">
        <?php echo $form->field($model, 'license')->textInput(['maxlength' => true, 'placeholder' => Yii::t('app', 'Enter License')]) ?>

        <?php echo $form->field($model, 'doctor_type')->inline()->radioList(Doctor::getDoctorType()) ?>

        <?php echo $form->field($model, 'gender')->inline()->radioList(UserProfile::getGenderList()) ?>
    </div>
</div>

<br/>
<div class="row">
    <div class="col-sm-12">
    <?php echo $form->field($model, 'biography')->textarea(['maxlength' => true, 'placeholder' => Yii::t('app', 'Enter some informatio about yourself (max 1000 characters)')]) ?>
    </div >
</div>


<h3><span><?php echo Yii::t('app', 'Clinic information') ?></span></h3>

<div class="row">
    <div class="col-sm-6">
        <?php echo $form->field($model, 'clinic')->textInput(['maxlength' => true, 'placeholder' => Yii::t('common', 'Enter Clinic Name')]) ?>
    </div>

    <div class="col-sm-6">
        <?php echo $form->field($model, 'address')->textInput(['maxlength' => true, 'placeholder' => Yii::t('common', 'Enter Clinic Address')]) ?>
    </div>

</div>

<div class="row">
    <div class="col-sm-6">
        <?php echo $form->field($model, 'city')->textInput(['maxlength' => true, 'placeholder' => Yii::t('common', 'Enter Clinic City')]) ?>
    </div>

    <div class="col-sm-6">
        <?php echo $form->field($model, 'state_id')->dropDownList(State::getStates(), ['prompt' => Yii::t('app', 'Select state')]) ?>
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        <?php echo $form->field($model, 'zipcode')->textInput(['maxlength' => true, 'placeholder' => Yii::t('common', 'Enter Zip Code')]) ?>
    </div>

    <div class="col-sm-6">
        <?php echo $form->field($model, 'phone')->textInput(['maxlength' => true, 'placeholder' => Yii::t('common', 'Enter Phone')]) ?>
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        <?php echo $form->field($model, 'fax')->textInput(['maxlength' => true, 'placeholder' => Yii::t('common', 'Enter Fax')]) ?>
    </div>

    <div class="col-sm-6">
        <?php echo $form->field($model, 'website')->textInput(['maxlength' => true, 'placeholder' => Yii::t('common', 'Enter Website')]) ?>
    </div>
</div>
