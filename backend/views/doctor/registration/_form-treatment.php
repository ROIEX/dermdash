<?php

use yii\helpers\Inflector;
use yii\helpers\Html;
use common\models\Brand;

?>

<?php foreach ($treatments as $treatment) : ?>
    <div class="panel" id="<?= 'treatment-panel' . $treatment->id ?>">
        <div class="panel-heading">
            <h4 class="panel-title">
                <span><?php echo $treatment->name . ' ' . ($treatment->reg_description ? $treatment->reg_description : '') ?></span>
                <a class="collapse-button open" data-toggle="collapse"
                   data-target="<?= '#treatment-collapse' . $treatment->id ?>" href="#collapseOne" aria-expanded="false"></a>
            </h4>
        </div>
        <div id="<?= 'treatment-collapse' . $treatment->id ?>" class="panel-collapse collapse">
            <div class="panel-body">

                <?php
                /**
                 * If treatment has no input for prices we generate checkbox input
                 */
                if (!empty($treatment->treatmentParams[0]->filledSeverity) || !empty($treatment->treatmentParams[0]->brandProvided) || !empty($treatment->treatmentIntensity)) : ?>
                    <?php echo $form->field($model, "brand_provided_treatments[$treatment->id]")->checkbox()
                        ->label(Yii::t('app', 'Please check box in order to receive submissions')) ?>
                <?php endif ?>
                <div class="row">
                    <?php foreach ($treatment->treatmentParams as $param) : ?>
                        <?php if (!empty($param->filledSeverity) || !empty($param->brandProvided) || !empty($treatment->treatmentIntensity)) : ?>
                            <div class="col-xs-12">
                                <div class="row">
                                    <?php
                                    /**
                                     * This is a shitcode, if you`re looking at this, don`t touch it, i didn`t want to write it, but i had to
                                     */
                                    if ($treatment->id == 27) {
                                        $counturing_list = Brand::find()->where(['in', 'id', [26, 17, 39]])->all();
                                        foreach ($counturing_list as $countouring_brand) : ?>
                                            <div class="col-xs-6">
                                               <h4 class="panel-title">
                                                   <span><?php echo $countouring_brand->name . ' ' . ($countouring_brand->reg_description ? $countouring_brand->reg_description : ''); ?></span>
                                               </h4>
                                                <?php foreach ($countouring_brand->brandParams as $param) : ?>
                                                    <?php $label =  $param->reg_description ? $param->reg_description : (($param->value == 1 || !is_numeric($param->value)) ?
                                                            ($param->value) :
                                                            ($param->value) . ' ' . Yii::t('app', '(total price of {quantity} {per})',
                                                                ['quantity' => $param->value,
                                                                    'per' => Inflector::pluralize(Brand::getPer($countouring_brand->per))
                                                                ])); ?>
                                                    <?php echo Html::activeLabel($model, "treatment_discounts[$treatment->id][$session->id]", ['label' => $label]) ?>
                                                    <div class="units-dollar">
                                                        <?php echo $form->field($model, "brands[$param->id]")->textInput(['placeholder' => Yii::t('app', 'Enter Price')])->label(false) ?>
                                                    </div>
                                                <?php endforeach ?>
                                            </div>
                                        <?php endforeach ?>
                                    <?php } if ($treatment->id == 8) {
                                        $fine_lines_list = Brand::find()->where(['in', 'id', [16, 28, 18]])->all(); ?>
                                        <?php foreach ($fine_lines_list as $brand) : ?>
                                            <div class="col-xs-6">
                                                <h4>
                                                    <span><?php echo $brand->name . ' ' . ($brand->reg_description ? $brand->reg_description : ''); ?></span>
                                                    <a class="collapse-button open" data-toggle="collapse" data-target="<?= '#brand-collapse' . $brand->id ?>" href="#collapseOne"></a>
                                                </h4>
                                                <?php echo Html::activeLabel($model, "dropdown_price[$brand->id]") ?>
                                                <div class="units-dollar">
                                                    <?php echo $form->field($model, "dropdown_price[$brand->id]")->textInput(['placeholder' => Yii::t('app', 'Enter Price')])->label(false) ?>
                                                </div>
                                            </div>
                                        <?php endforeach ?>
                                    <?php } elseif ($treatment->id == 28) { ?>
                                        <?php $fillers_list = Brand::find()->where(['in', 'id', [24, 20, 15, 19, 21, 22, 23, 25]])->all();
                                        foreach ($fillers_list as $fillers_brand) : ?>
                                            <div class="col-xs-6">
                                                <h4 class="panel-title">
                                                    <span><?php echo $fillers_brand->name . ' ' . ($fillers_brand->reg_description ? $fillers_brand->reg_description : ''); ?></span>
                                                </h4>

                                                <?php foreach ($fillers_brand->brandParams as $param) : ?>
                                                    <?php $label =  $param->reg_description ? $param->reg_description : (($param->value == 1) ?
                                                            ($param->value . " " . Brand::getPer($fillers_brand->per)) :
                                                            ($param->value . " " . Inflector::pluralize(Brand::getPer($fillers_brand->per))) . ' ' . Yii::t('app', '(total price of {quantity} {per})',
                                                                ['quantity' => $param->value,
                                                                    'per' => Inflector::pluralize(Brand::getPer($fillers_brand->per)),

                                                                ])); ?>
                                                    <?php echo Html::activeLabel($model, "treatment_discounts[$treatment->id][$session->id]", ['label' => $label]) ?>
                                                    <div class="units-dollar">
                                                        <?php echo $form->field($model, "brands[$param->id]")->textInput(['placeholder' => Yii::t('app', 'Enter Price')])->label(false) ?>
                                                    </div>
                                                <?php endforeach ?>
                                            </div>
                                        <?php endforeach ?>

                                    <?php } elseif ($treatment->id == 3) { ?>
                                        <?php $peel_list = Brand::find()->where(['in', 'id', [29, 33, 32, 31, 30]])->all();

                                        foreach ($peel_list as $peel_brand) : ?>
                                            <div class="col-xs-6">
                                                <h4 class="panel-title">
                                                    <span><?php echo $peel_brand->name . ' ' . ($peel_brand->reg_description ? $peel_brand->reg_description : ''); ?></span>
                                                </h4>
                                                <?php foreach ($peel_brand->brandParams as $param) : ?>
                                                    <?php $label =  $param->reg_description ? $param->reg_description : (($param->value == 1) ?
                                                            ($param->value . " " . Brand::getPer($peel_brand->per)) :
                                                            ($param->value . " " . Inflector::pluralize(Brand::getPer($peel_brand->per))) . ' ' . Yii::t('app', '(total price of {quantity} {per})',
                                                                ['quantity' => $param->value,
                                                                    'per' => Inflector::pluralize(Brand::getPer($peel_brand->per)),

                                                                ])); ?>
                                                    <?php echo Html::activeLabel($model, "treatment_discounts[$treatment->id][$session->id]", ['label' => $label]) ?>
                                                    <div class="units-dollar">
                                                        <?php echo $form->field($model, "brands[$param->id]")->textInput(['placeholder' => Yii::t('app', 'Enter Price')])->label(false) ?>
                                                    </div>
                                                <?php endforeach ?>
                                            </div>
                                        <?php endforeach ?>
                                    <?php } ?>
                                </div>
                            </div>
                            <?php break; ?>
                        <?php else : ?>
                            <div class="col-xs-6">
                                <?php $label = $param->reg_description ? $param->reg_description : $param->value ?>
                                <?php echo Html::activeLabel($model, "treatments[$param->id]", ['label' => $label]) ?>
                                <div class="units-dollar">
                                    <?php echo $form->field($model, "treatments[$param->id]")->textInput(['placeholder' => Yii::t('app', 'Enter Price')])->label(false) ?>
                                </div>
                            </div>
                        <?php endif ?>
                    <?php endforeach ?>
                </div>
                <div class="row">
                    <?php foreach ($treatment->treatmentSessions as $key => $session) : ?>
                        <?php if ($session->session_count > 1) : ?>
                            <div class="col-xs-6">
                                <?php echo Html::activeLabel($model, "treatment_discounts[$treatment->id][$session->id]", ['label' => $treatment->name . ', ' . $session->session_count . " " . Yii::t('app', 'Sessions')]) ?>
                                <div class="units-percentage">
                                    <?php echo $form->field($model, "treatment_discounts[$treatment->id][$session->id]")->textInput(['placeholder' => Yii::t('app', 'Off')])->label(false) ?>
                                </div>
                            </div>
                        <?php endif ?>
                    <?php endforeach ?>
                </div>
                    <div class="row">
                    <?php if ($treatment->id == 3) { ?>
                        <?php $peel_list = Brand::find()->where(['id' => 27])->all();

                        foreach ($peel_list as $peel_brand) : ?>
                            <div class="col-xs-6">
                                <h4 class="panel-title">
                                    <span><?php echo $peel_brand->name . ' ' . ($peel_brand->reg_description ? $peel_brand->reg_description : ''); ?></span>
                                </h4>
                                <?php foreach ($peel_brand->brandParams as $param) : ?>
                                    <?php $label =  $param->reg_description ? $param->reg_description : (($param->value == 1) ?
                                        ($param->value . " " . Brand::getPer($peel_brand->per)) :
                                        ($param->value . " " . Inflector::pluralize(Brand::getPer($peel_brand->per))) . ' ' . Yii::t('app', '(total price of {quantity} {per})',
                                            ['quantity' => $param->value,
                                                'per' => Inflector::pluralize(Brand::getPer($peel_brand->per)),

                                            ])); ?>
                                    <?php echo Html::activeLabel($model, "treatment_discounts[$treatment->id][$session->id]", ['label' => $label]) ?>
                                    <div class="units-dollar">
                                        <?php echo $form->field($model, "brands[$param->id]")->textInput(['placeholder' => Yii::t('app', 'Enter Price')])->label(false) ?>
                                    </div>
                                <?php endforeach ?>
                            </div>
                        <?php endforeach ?>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
<?php endforeach ?>
