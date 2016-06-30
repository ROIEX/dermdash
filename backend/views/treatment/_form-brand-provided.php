<?php
/* @var $this \yii\web\View */
use common\components\dynForm\DynamicFormWidget;
use yii\helpers\Html;
use kartik\select2\Select2;
?>

<?php
DynamicFormWidget::begin([
    'widgetContainer' => 'dynamicform_inner',
    'widgetBody' => '.container-bp',
    'widgetItem' => '.bp-item',
    'limit' => 20,
    'min' => 1,
    'insertButton' => '.add-bp',
    'deleteButton' => '.remove-bp',
    'model' => $brand_provided_models[0],
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
                    <button type="button" class="add-bp btn btn-success btn-xs"><span class="glyphicon glyphicon-plus"></span></button>
                </th>
            </tr>
        </thead>

        <tbody class="container-bp">

        <?php foreach ($brand_provided_models as $indexBrandProvided => $modelBrandProvided): ?>

            <tr class="bp-item">
                <td class="vcenter">

                    <?php
                     //necessary for update action.
                    if (! $modelBrandProvided->isNewRecord) {
                        echo Html::activeHiddenInput($modelBrandProvided, "[{$indexParam}][{$indexBrandProvided}]id");
                    }
                    ?>

                    <?php echo Select2::widget([
                        'model' => $modelBrandProvided,
                        'attribute' => "[{$indexParam}][{$indexBrandProvided}]brand_param_id",
                        'data' => \common\models\Brand::getBrands(),
                        'options' => ['placeholder' => Yii::t('app', 'Select brand param')],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]);
                    ?>

                    <?php echo $form->field($modelBrandProvided, "[{$indexParam}][{$indexBrandProvided}]count")->textInput() ?>

                </td>

            </tr>

        <?php endforeach; ?>

        </tbody>

    </table>

<?php DynamicFormWidget::end(); ?>

