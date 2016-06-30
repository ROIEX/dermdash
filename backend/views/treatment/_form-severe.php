<?php
/* @var $this \yii\web\View */
use common\components\dynForm\DynamicFormWidget;
use yii\helpers\Html;
use kartik\select2\Select2;
?>

<?php
DynamicFormWidget::begin([
    'widgetContainer' => 'dynamicform_inner',
    'widgetBody' => '.container-severe',
    'widgetItem' => '.severe-item',
    'limit' => 20,
    'min' => 1,
    'insertButton' => '.add-severe',
    'deleteButton' => '.remove-severe',
    'model' => $severity_models[0],
    'formId' => 'treatment-form',
    'formFields' => [
        'param_id',
        'severity_id',
        'brand_id',
        'count',
    ],
]); ?>

    <table class="table table-bordered">

        <thead>
            <tr>
                <th>Description</th>
                <th class="text-center">
                    <button type="button" class="add-severe btn btn-success btn-xs"><span class="glyphicon glyphicon-plus"></span></button>
                </th>
            </tr>
        </thead>

        <tbody class="container-severe">

        <?php foreach ($severity_models as $indexSeverity => $modelSeverity): ?>

            <tr class="severe-item">
                <td class="vcenter">

                    <?php
                     //necessary for update action.
                    if (! $modelSeverity->isNewRecord) {
                        echo Html::activeHiddenInput($modelSeverity, "[{$indexParam}][{$indexSeverity}]id");
                    }
                    ?>

                    <?php echo $form->field($modelSeverity, "[{$indexParam}][{$indexSeverity}]severity_id")->dropDownList(\common\models\Severity::getActiveSeverities(), ['prompt' => Yii::t('app', 'Select severity')]) ?>

                    <?php echo Select2::widget([
                    'model' => $modelSeverity,
                    'attribute' => "[{$indexParam}][{$indexSeverity}]brand_param_id",
                    'data' => \common\models\Brand::getBrands(),
                    'options' => ['placeholder' => Yii::t('app', 'Select brand param')],
                    'pluginOptions' => [
                    'allowClear' => true
                    ],
                    ]);
                    ?>

                    <?php echo $form->field($modelSeverity, "[{$indexParam}][{$indexSeverity}]count")->textInput() ?>

                    <?php if ($modelSeverity->icon_url && $modelSeverity->icon_path) {
                        echo Html::img($modelSeverity->icon_url . '/' . $modelSeverity->icon_path, ['width' => 75, 'height' => 75]);
                    }?>

                    <?php echo $form->field($modelSeverity, "[{$indexParam}][{$indexSeverity}]uploaded_image")->fileInput()->label(false)?>

                </td>
            </tr>

        <?php endforeach; ?>

        </tbody>

    </table>

<?php DynamicFormWidget::end(); ?>

