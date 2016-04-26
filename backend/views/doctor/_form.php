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

        <div class="form-section">

<!--            <h3><span>--><?php //echo Yii::t('app', 'Procedure Prices:') ?><!--</span></h3>-->
<!--            <h4>--><?php //echo Yii::t('app', '(Please fill in the prices for procedures you would like to be listed for in our system. Leave blank any procedures that you do not perform.)') ?><!--</h4>-->
<!---->
<!--            --><?php //if (!empty($treatments)) { ?>
<!---->
<!--                <div id="accordion">-->
<!---->
<!--                    --><?php //echo $this->render('registration/_form-treatment', ['model' => $model, 'form' => $form, 'treatments' => $treatments]) ?>
<!---->
<!--                </div>-->
<!---->
<!--            --><?php //} ?>
<!--        </div>-->
<!---->
<!--        <div class="form-section">-->
<!---->
<!--        --><?php //if (!empty($brands)) { ?>
<!---->
<!--            <div id="accordion">-->
<!--                --><?php //foreach ($brands as $brand) { ?>
<!--                    --><?php //if (!in_array($brand->id, [39, 26, 16, 28, 18, 17, 20, 24, 15, 19, 21, 22, 23, 25, 27, 29, 33, 32, 31, 30])) { ?>
<!--                        --><?php //echo $this->render('registration/_form-brand', ['model' => $model, 'form' => $form, 'brand' => $brand]) ?>
<!--                    --><?php //} ?>
<!--                --><?php //} ?>
<!--            </div>-->
<!--        --><?php //} ?>

        </div>

        <?php echo $this->render('registration/_form-footer', ['model' => $model, 'form' => $form]) ?>

        <?php ActiveForm::end(); ?>

    </div>
