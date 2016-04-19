<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
use wbraganca\dynamicform\DynamicFormWidget;
use common\models\Treatment;
use common\models\Brand;
use budyaga\cropper\Widget;

/* @var $this yii\web\View */
/* @var $model common\models\Brand */
/* @var $form yii\bootstrap\ActiveForm */

$js = <<<SCRP

$(document).ready(function(){
    if($('#brand-need_count').is(':checked')) {
        $("#attribute").hide();
    }
});

$('.delete_photo').click(function() {
    $(".thumbnail").attr("src", '');
});

$('#brand-need_count').click(function() {
    $("#attribute").toggle('slow');
});

SCRP;
$this->registerJs($js);
?>

<div class="brand-form">

    <?php $form = ActiveForm::begin(['id' => 'brand-form', 'options' => ['enctype' => 'multipart/form-data']]); ?>

    <?php echo $form->errorSummary($model); ?>

    <?php echo $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?php echo $form->field($model, 'sub_string')->textarea(['rows' => 6]) ?>

    <?php echo $form->field($model, 'instruction')->textarea(['rows' => 6]) ?>

    <?php echo $form->field($model, 'reg_description')->textInput(['maxlength' => true]) ?>

    <?php echo $form->field($model, 'uploaded_image')->widget(Widget::className(), [
        'uploadUrl' => Url::toRoute('/file-storage/uploadPhoto'),
    ]) ?>

    <?php echo $form->field($model, 'per')->dropDownList(Brand::getPerArray(), ['prompt' => Yii::t('app', 'Brand per')]) ?>

    <?php echo $form->field($model, 'treatment_id')->dropDownList(ArrayHelper::map(Treatment::find()->all(), 'id', 'name'), ['prompt' => Yii::t('app', 'Select treatment if needed')]) ?>

    <?php echo $form->field($model, 'type')->dropDownList(Brand::getTypeArray(), ['prompt' => Yii::t('app', 'Select type if needed')]) ?>

    <?php echo $form->field($model, 'status')->checkbox() ?>

    <?php echo $form->field($model, 'param_multiselect')->checkbox() ?>

    <?php echo $form->field($model, 'is_dropdown')->checkbox() ?>

    <div class="panel panel-default" id="attribute">
        <div class="panel-heading">
            <h4>
                <i class="glyphicon glyphicon-plus"></i><?php echo Yii::t('app', 'Values')?>
            </h4>
        </div>
        <div class="panel-body">
            <?php DynamicFormWidget::begin([
                'widgetContainer' => 'dynamicform_wrapper', // required: only alphanumeric characters plus "_" [A-Za-z0-9_]
                'widgetBody' => '.container-items', // required: css class selector
                'widgetItem' => '.item', // required: css class
                //'limit' => 20, // the maximum times, an element can be cloned (default 999)
                'min' => 1, // 0 or 1 (default 1)
                'insertButton' => '.add-item', // css class
                'deleteButton' => '.remove-item', // css class
                'model' => $param_models[0],
                'formId' => 'brand-form',
                'formFields' => [
                    'value',
                    'status',
                    'reg_description'
                ],
            ]); ?>

            <div class="container-items"><!-- widgetContainer -->

                <?php foreach ($param_models as $i => $param): ?>
                    <div class="item panel panel-default"><!-- widgetBody -->
                        <div class="panel-heading">
                            <h3 class="panel-title pull-left"><?php echo Yii::t('app', 'Value')?></h3>
                            <div class="pull-right">
                                <button type="button" class="add-item btn btn-success btn-xs"><i class="glyphicon glyphicon-plus"></i></button>
                                <button type="button" class="remove-item btn btn-danger btn-xs"><i class="glyphicon glyphicon-minus"></i></button>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="panel-body">
                            <?php
                            if (!$param->isNewRecord) {
                                echo Html::activeHiddenInput($param, "[{$i}]id");
                            }
                            ?>
                            <div class="row">
                                <div class="col-sm-6">

                                    <?php echo $form->field($param, "[{$i}]value")->textInput(['maxlength' => true]) ?>

                                    <?php echo $form->field($param, "[{$i}]reg_description")->textInput(['maxlength' => true]) ?>

                                    <?php echo $form->field($param, "[{$i}]body_part")->dropDownList(\common\models\Brand::getBodyPartArray(), ['prompt' => Yii::t('app', 'Define bodypart for app')]) ?>

                                    <?php echo $form->field($param, "[{$i}]status")->checkbox() ?>

                                    <?php if ($param->icon_base_url && $param->icon_path) {
                                        echo Html::img($param->icon_base_url . '/' . $param->icon_path, ['width' => 75, 'height' => 75]);
                                    }?>

                                    <?php echo $form->field($param, "[{$i}]uploaded_image")->fileInput()->label(false)?>

                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <?php DynamicFormWidget::end(); ?>
        </div>
    </div>

    <div class="form-group">
        <?php echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
