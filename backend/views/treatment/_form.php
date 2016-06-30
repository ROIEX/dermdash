<?php

use common\components\dynForm\DynamicFormWidget;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use budyaga\cropper\Widget;

/* @var $this yii\web\View */
/* @var $model common\models\Treatment */
/* @var $form yii\bootstrap\ActiveForm */
$js = <<<SCRP
    if($('#treatment-per_item').is(':checked')) {
        $("#severe").hide();
    }

    if($('#treatment-intensity').is(':checked')) {
        $("#intensity-form").show();
    }

    if($('#treatment-per_session').is(':checked')) {
        $("#session").hide();
    }

    $('.delete_photo').click(function() {
    $(".thumbnail").attr("src", '');
    });

    $('#treatment-per_item').click(function() {
        $("#severe").toggle('slow');
    });

    $('#treatment-per_session').click(function() {
        $("#session").toggle('slow');
    });

    $('#treatment-intensity').click(function() {
        $("#intensity-form").toggle('slow');
    });

SCRP;
$this->registerJs($js);

?>

<div class="treatment-form">

    <?php $form = ActiveForm::begin(['id' => 'treatment-form', 'options' => ['enctype' => 'multipart/form-data']]); ?>

    <?php echo $form->errorSummary($model); ?>

    <?php echo $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?php echo $form->field($model, 'app_name')->textInput(['maxlength' => true]) ?>

    <?php echo $form->field($model, 'sub_string')->textarea(['rows' => 6]) ?>

    <?php echo $form->field($model, 'reg_description')->textInput(['maxlength' => true]) ?>

    <?php echo $form->field($model, 'uploaded_image')->widget(Widget::className(), [
        'uploadUrl' => Url::toRoute('/file-storage/uploadPhoto'),
    ]) ?>

    <?php echo $form->field($model, 'status')->checkbox() ?>

    <?php echo $form->field($model, 'param_multiselect')->checkbox() ?>

    <?php echo $form->field($model, 'per_item')->checkbox() ?>

    <?php echo $form->field($model, 'per_session')->checkbox() ?>

    <?php echo $form->field($model, 'intensity')->checkbox() ?>

    <?php echo $form->field($model, 'select_both_button')->checkbox() ?>

    <?php echo $form->field($model, 'session_buttons_position')->checkbox() ?>

    <?php echo $form->field($model, 'buttons_in_row')->textInput() ?>

    <div class="panel panel-default" id="intensity-form" hidden="true">

        <div class="panel-heading">
            <h4>
                <i class="glyphicon glyphicon-plus"></i><?php echo Yii::t('app', 'Intensity section') ?>
            </h4>
        </div>
        <div class="panel-body">
            <?php DynamicFormWidget::begin([
                'widgetContainer' => 'dynamicform_wrapper', // required: only alphanumeric characters plus "_" [A-Za-z0-9_]
                'widgetBody' => '.container-intensity-items', // required: css class selector
                'widgetItem' => '.item-intensity', // required: css class
                'limit' => 20, // the maximum times, an element can be cloned (default 999)
                'min' => 1, // 0 or 1 (default 1)
                'insertButton' => '.add-intensity', // css class
                'deleteButton' => '.remove-intensity', // css class
                'model' => $intensity_models[0],
                'formId' => 'treatment-form',
                'formFields' => [
                    'intensity_id',
                    'brand_param_id',
                    'count',
                ],
            ]); ?>

            <div class="container-intensity-items"><!-- widgetContainer -->

                <?php foreach ($intensity_models as $i => $intensity): ?>
                    <div class="item-intensity panel panel-default"><!-- widgetBody -->
                        <div class="panel-heading">
                            <h3 class="panel-title pull-left"><?php echo Yii::t('app', 'Treatment intensities') ?></h3>

                            <div class="pull-right">
                                <button type="button" class="add-intensity btn btn-success btn-xs"><i
                                        class="glyphicon glyphicon-plus"></i></button>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="panel-body">
                            <?php
                            if (!$intensity->isNewRecord) {
                                echo Html::activeHiddenInput($intensity, "[{$i}]id");
                            }
                            ?>
                            <div class="row">
                                <div class="col-sm-6">

                                    <?php echo $form->field($intensity, "[{$i}]intensity_id")->dropDownList(ArrayHelper::map(\common\models\Intensity::find()->all(), 'id', 'name'), ['prompt' => Yii::t('app', 'Select intensity')]) ?>

                                    <?php echo Select2::widget([
                                        'model' => $intensity,
                                        'attribute' => "[{$i}]brand_param_id",
                                        'data' => \common\models\Brand::getBrands(),
                                        'options' => ['placeholder' => Yii::t('app', 'Select brand param')],
                                        'pluginOptions' => [
                                            'allowClear' => true
                                        ],
                                    ]);
                                    ?>

                                    <?php echo $form->field($intensity, "[{$i}]count")->textInput(['maxlength' => true]) ?>

                                    <?php echo $form->field($intensity, "[{$i}]status")->checkbox() ?>

                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <?php DynamicFormWidget::end(); ?>
        </div>
    </div>

    <div class="panel panel-default" id="severe">

        <div class="padding-v-md">

            <div class="line line-dashed"></div>

        </div>

        <?php DynamicFormWidget::begin([
            'widgetContainer' => 'dynamicform_wrapper',
            'widgetBody' => '.param-items',
            'widgetItem' => '.param-item',
            'limit' => 20,
            'min' => 1,
            'insertButton' => '.add-param',
            'deleteButton' => '.remove-param',
            'model' => $param_models[0],
            'formId' => 'treatment-form',
            'formFields' => [
                'value',
                'status',
            ],
        ]); ?>

        <table class="table table-bordered table-striped">

            <thead>
            <tr>
                <th><?php echo Yii::t('app', 'Treatment params') ?></th>

                <th style="width: 450px;"><?php echo Yii::t('app', 'Param severe') ?></th>

                <th class="text-center" style="width: 90px;">
                    <button type="button" class="add-param btn btn-success btn-xs"><span class="fa fa-plus"></span>
                    </button>
                </th>
            </tr>
            </thead>

            <tbody class="param-items">
            <?php foreach ($param_models as $indexParam => $modelParam): ?>

                <tr class="param-item">
                    <td class="vcenter">

                        <?php
                        // necessary for update action.
                        if (!$modelParam->isNewRecord) {
                            echo Html::activeHiddenInput($modelParam, "[{$indexParam}]id");
                        }
                        ?>

                        <?= $form->field($modelParam, "[{$indexParam}]value")->textInput(['maxlength' => true]) ?>

                        <?php echo $form->field($modelParam, "[{$indexParam}]reg_description")->textInput(['maxlength' => true]) ?>

                        <?= $form->field($modelParam, "[{$indexParam}]status")->checkbox() ?>

                        <?php if ($modelParam->icon_base_url && $modelParam->icon_path) {
                            echo Html::img($modelParam->icon_base_url . '/' . $modelParam->icon_path, ['width' => 75, 'height' => 75]);
                        }?>

                        <?php echo $form->field($modelParam, "[{$indexParam}]uploaded_image")->fileInput()->label(false)?>

                    </td>
                    <td>

                        <?= $this->render('_form-severe', [
                            'form' => $form,
                            'indexParam' => $indexParam,
                            'severity_models' => $severity_models[$indexParam],
                        ]) ?>

                    </td>

                    <td class="text-center vcenter" style="width: 90px; verti">

                    </td>

                </tr>

            <?php endforeach; ?>

            </tbody>

        </table>

        <?php DynamicFormWidget::end(); ?>

    </div>

    <div class="panel panel-default" id="session">

        <div class="panel-heading">
            <h4>
                <i class="glyphicon glyphicon-plus"></i><?php echo Yii::t('app', 'Session section') ?>
            </h4>
        </div>
        <div class="panel-body">
            <?php DynamicFormWidget::begin([
                'widgetContainer' => 'dynamicform_wrapper', // required: only alphanumeric characters plus "_" [A-Za-z0-9_]
                'widgetBody' => '.container-session-items', // required: css class selector
                'widgetItem' => '.item-session', // required: css class
                'limit' => 20, // the maximum times, an element can be cloned (default 999)
                'min' => 1, // 0 or 1 (default 1)
                'insertButton' => '.add-item', // css class
                'deleteButton' => '.remove-item', // css class
                'model' => $session_models[0],
                'formId' => 'treatment-form',
                'formFields' => [
                    'value',
                    'status',
                ],
            ]); ?>

            <div class="container-session-items"><!-- widgetContainer -->

                <?php foreach ($session_models as $i => $session): ?>
                    <div class="item-session panel panel-default"><!-- widgetBody -->
                        <div class="panel-heading">
                            <h3 class="panel-title pull-left"><?php echo Yii::t('app', 'Treatment session count') ?></h3>

                            <div class="pull-right">
                                <button type="button" class="add-item btn btn-success btn-xs"><i
                                        class="glyphicon glyphicon-plus"></i></button>

                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="panel-body">
                            <?php
                            if (!$session->isNewRecord) {
                                echo Html::activeHiddenInput($session, "[{$i}]id");
                            }
                            ?>
                            <div class="row">
                                <div class="col-sm-6">

                                    <?php echo $form->field($session, "[{$i}]session_count")->textInput(['maxlength' => true]) ?>

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



<?php
$js = <<<SCRP

    $(".dynamicform_inner").on("afterInsert", function(e, item) {
        $(item).find("select").val("");
         $(item).find("img").removeAttr("src");
    });

    $(".dynamicform_wrapper").on("afterInsert", function(e, item) {
        $(item).find("select").val("");
    });

    var add_handler;
    jQuery._data( $("#treatment-form")[0], "events" ).click.forEach(function(val) {
        if(val.selector === ".add-severe") {
            add_handler = val.handler;
            return false;
        }
    })
    jQuery("#treatment-form").off("click", ".add-severe");
    jQuery("#treatment-form").on("click", ".add-severe", add_handler);


    var remove_handler;
    jQuery._data( $("#treatment-form")[0], "events" ).click.forEach(function(val) {
        if(val.selector === ".remove-severe") {
            remove_handler = val.handler;
            return false;
        }
    })
    jQuery("#treatment-form").off("click", ".remove-severe");
    jQuery("#treatment-form").on("click", ".remove-severe", remove_handler)

    jQuery._data( $("#treatment-form")[0], "events" ).click.forEach(function(val) {
        console.log(val.selector);
    })

SCRP;
$this->registerJs($js);

?>