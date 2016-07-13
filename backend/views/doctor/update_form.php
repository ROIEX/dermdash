<?php

use yii\helpers\Html;
use yii\widgets\MaskedInput;
use yii\bootstrap\ActiveForm;
use common\models\Doctor;
use common\models\Brand;
use common\models\UserProfile;
use common\models\State;
use yii\web\JsExpression;
use yii\helpers\Inflector;

/* @var $this yii\web\View */
/* @var $doctor_model common\models\Doctor */
/* @var $form yii\bootstrap\ActiveForm */

$this->registerCssFile(Yii::getAlias('@backendUrl' . '/css/doctor-create.css'));
$this->registerCssFile(Yii::getAlias('https://fonts.googleapis.com/css?family=Muli:300'));
?>
<div class="content">
    <div class="form-section">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data'],
       'enableAjaxValidation' => true
    ]); ?>

    <?php echo $form->errorSummary($model->getModel('user_profile')); ?>

        <h3><span><?php echo Yii::t('app', 'Doctor information') ?></span></h3>

        <div class="row">
            <div class="col-sm-6">
                <?php echo $form->field($model->getModel('user_profile'), 'firstname')->textInput(['maxlength' => true, 'placeholder' => Yii::t('app', 'Enter First Name')]) ?>
            </div>

            <div class="col-sm-6">
                <?php echo $form->field($model->getModel('user_profile'), 'lastname')->textInput(['maxlength' => true, 'placeholder' => Yii::t('app', 'Enter Last Name')]) ?>
            </div>
        </div>

        <div class="row">
            <?php echo Yii::$app->session->getFlash('existing_email'); ?>
            <div class="col-sm-6">
                <?php echo $form->field($model->getModel('user_model'), 'email')->textInput(['maxlength' => 32, 'placeholder' => Yii::t('app', 'Enter Email')]) ?>
            </div>

            <div class="col-sm-6">
                <?php echo $form->field($model->getModel('user_profile'), 'date_of_birth')->widget(MaskedInput::className(),[
                    'clientOptions' => [
                        'maskAlias'=> 'date',
                        'alias' => 'm/d/y',
                    ]
                ])?>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-6">
                <?php echo $form->field($model->getModel('user_profile'), 'uploaded_image')->widget('\trntv\filekit\widget\Upload', [
                    'url' => ['/file-storage/upload'],
                    'sortable' => true,
                    'acceptFileTypes' => new JsExpression('/(\.|\/)(jpe?g|png)$/i'),
                    'maxFileSize' => 10 * 1024 * 1024, // 10 MiB,
                ])->label(Yii::t('app', 'Add Photo')); ?>

                <?php echo \yii\helpers\Html::activeHint($model, 'uploaded_image', [ 'hint' => Yii::t('app', 'Image size must be less than 10 Mb')])?>
            </div>

            <div class="col-sm-6">
                <?php echo $form->field($model->getModel('doctor_model'), 'license')->textInput(['maxlength' => true, 'placeholder' => Yii::t('app', 'Enter License')]) ?>

                <?php echo $form->field($model->getModel('doctor_model'), 'doctor_type')->inline()->radioList(Doctor::getDoctorType()) ?>

                <?php echo $form->field($model->getModel('user_profile'), 'gender')->inline()->radioList(UserProfile::getGenderList()) ?>
            </div>
        </div>

        <br/>
        <div class="row">
            <div class="col-sm-12">
                <?php echo $form->field($model->getModel('doctor_model'), 'biography')->textarea(['placeholder' => Yii::t('app', 'Enter some informatio about yourself (max 1000 characters)')]) ?>
            </div >
        </div>

        <?php if(Yii::$app->user->can('administrator')) : ?>
        <div class="row">
            <div class="col-sm-12">
                <?php echo $form->field($model->getModel('doctor_model'), 'add_info')->textInput(['maxlength' => true, 'placeholder' => Yii::t('app', 'Additional info for application')]) ?>
            </div >
        </div>
        <?php endif ?>



        <h3><span><?php echo Yii::t('app', 'Clinic information') ?></span></h3>

        <div class="row">
            <div class="col-sm-6">
                <?php echo $form->field($model->getModel('doctor_model'), 'clinic')->textInput(['maxlength' => true, 'placeholder' => Yii::t('common', 'Enter Clinic Name')]) ?>
            </div>

            <div class="col-sm-6">
                <?php echo $form->field($model->getModel('user_profile'), 'address')->textInput(['maxlength' => true, 'placeholder' => Yii::t('common', 'Enter Clinic Address')]) ?>
            </div>

        </div>

        <div class="row">
            <div class="col-sm-6">
                <?php echo $form->field($model->getModel('user_profile'), 'city')->textInput(['maxlength' => true, 'placeholder' => Yii::t('common', 'Enter Clinic City')]) ?>
            </div>

            <div class="col-sm-6">
                <?php echo $form->field($model->getModel('user_profile'), 'state_id')->dropDownList(State::getStates(), ['prompt' => Yii::t('app', 'Select state')]) ?>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-6">
                <?php echo $form->field($model->getModel('user_profile'), 'zipcode')->textInput(['maxlength' => true, 'placeholder' => Yii::t('common', 'Enter Zip Code')]) ?>
            </div>

            <div class="col-sm-6">
                <?php echo $form->field($model->getModel('user_profile'), 'phone')->textInput(['maxlength' => true, 'placeholder' => Yii::t('common', 'Enter Phone')]) ?>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-6">
                <?php echo $form->field($model->getModel('doctor_model'), 'fax')->textInput(['maxlength' => true, 'placeholder' => Yii::t('common', 'Enter Fax')]) ?>
            </div>

            <div class="col-sm-6">
                <?php echo $form->field($model->getModel('doctor_model'), 'website')->textInput(['maxlength' => true, 'placeholder' => Yii::t('common', 'Enter Website')]) ?>
            </div>
        </div>

        <?php if(Yii::$app->user->can('administrator')) :?>
            <div class="row">
            <h3><span><?php echo Yii::t('app', 'Photo Section') ?></span></h3>
            <div class="col-sm-6">
                <?php echo $form->field($model->getModel('doctor_model'), 'uploaded_images')->widget('\trntv\filekit\widget\Upload', [
                    'url' => ['/file-storage/upload'],
                    'sortable' => true,
                    'multiple' => true,
                    'maxFileSize' => 20000000,
                    'maxNumberOfFiles' => 10
                ])->label(Yii::t('app', 'Photos')); ?>

                <?php echo \yii\helpers\Html::activeHint($model, 'uploaded_image', [ 'hint' => Yii::t('app', 'Image size must be less than 20 Mb')])?>
            </div>
            </div>
        <?php endif ?>

        <div class="form-section">

            <h3><span><?php echo Yii::t('app', 'Procedure Prices:') ?></span></h3>
            <h4><?php echo Yii::t('app', '(Please fill in the prices for procedures you would like to be listed for in our system. Leave blank any procedures that you do not perform.)') ?></h4>

            <?php if (!empty($treatments)) { ?>

                <div id="accordion">
                  
                    <?php echo $this->render('update/_form-treatment', [
                        'model' => $model->getModel('doctor_model'),
                        'form' => $form, 'treatments' => $treatments,
                        'selected_treatments' => $selected_treatments,
                        'brand_special' => $brand_special,
                        'treatment_special' => $treatment_special,
                        'selected_brands_dropdown_prices' => $selected_brands_dropdown_prices,
                        'dropdown_special' => $dropdown_special,
                        'selected_brands' => $selected_brands
                    ]) ?>

                </div>

            <?php } ?>
        </div>

        <?php if (!empty($brands)) { ?>

            <div id="accordion">
                <?php foreach ($brands as $brand) { ?>
                    <?php if (!in_array($brand->id, [39, 26, 16, 28, 18, 17, 20, 24, 15, 19, 21, 22, 23, 25, 27, 29, 33, 32, 31, 30])) { ?>
                        <?php echo $this->render('update/_form-brand', [
                            'model' => $model->getModel('doctor_model'),
                            'form' => $form, 
                            'brand' => $brand,
                            'dropdown_special' => $dropdown_special,
                            'brand_special' => $brand_special,
                            'selected_brands_dropdown_prices' => $selected_brands_dropdown_prices,
                            'selected_brands' => $selected_brands
                            ])
                        ?>
                    <?php } ?>
                <?php } ?>
            </div>
        <?php } ?>

    </div>

    <?php
        if (Yii::$app->user->can('administrator')) {
            echo $form->field($model->getModel('doctor_model'), 'status')->checkbox()->label(Yii::t('app', 'Status (Active / Not active)'));
        }
    ?>

    <div class="form-section">
        <div class="row">
            <div class="col-sm-8">
                <div class="form-group">
                    <?php echo $form->field($model->getModel('doctor_model'), 'signature')->textInput(['placeholder' => Yii::t('app', 'Enter name of the person filling out this form')]) ?>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group">
        <?php echo Html::submitButton(Yii::t('app', 'Update'), ['class' =>'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
</div>
