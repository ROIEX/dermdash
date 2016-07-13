<?php

use yii\helpers\Inflector;
use yii\helpers\Html;
use common\models\Brand;

?>

    <div class="panel" id="<?= 'brand-panel' . $brand->id ?>">
        <div class="panel-heading">
            <h4 class="panel-title">
                <span><?php echo $brand->name . ' ' . ($brand->reg_description ? $brand->reg_description : ''); ?></span>
                <a class="collapse-button open" data-toggle="collapse" data-target="<?= '#brand-collapse' . $brand->id ?>" href="#collapseOne" aria-expanded="false"></a>
            </h4>
        </div>
        <div id="<?= 'brand-collapse' . $brand->id ?>" class="panel-collapse collapse">
            <div class="panel-body">
                <?php if (!empty($brand->brandParams)) : ?>
                    <?php if ($brand->is_dropdown == 1) : ?>
                        <?php
                            $value = '';
                            $special_value = '';
                            if ($selected_brands_dropdown_prices) {
                                if (in_array($brand->defaultBrandParam->id, array_keys($selected_brands_dropdown_prices))) {
                                    $value = $selected_brands_dropdown_prices[$brand->defaultBrandParam->id];
                                }
                            }
                         ?>
                        <div class="col-xs-6">
                            <?php echo Html::activeLabel($model, "dropdown_price[$brand->id]") ?>
                            <div class="units-dollar">
                                <?php echo $form->field($model, "dropdown_price[$brand->id]")->textInput(['placeholder' => Yii::t('app', 'Enter Price'), 'value' => $value])->label(false) ?>
                            </div>
                            <div class="units-dollar">
                                <?php echo $form->field($model, "dropdown_special_price[$brand->id]")->textInput(['placeholder' => Yii::t('app', 'Enter Special Price'), 'value' => $special_value])->label(false) ?>
                            </div>
                        </div>
                    <?php else : ?>
                        <?php if($brand->id == 36) : ?>

                            <?php
                                $label_list = [];
                                foreach ($brand->brandParams as $param) : ?>
                                <?php
                                    $custom_label = explode(', ', $param->value);
                                    $label_list[] =  trim ($custom_label[0]);
                                ?>

                            <?php endforeach ?>

                            <?php $label_list = array_unique($label_list) ?>

                            <?php foreach ($label_list as $label) : ?>
                                <div class="col-xs-6">
                                <h4 class="panel-title">
                                    <span><?php echo $label; ?></span>
                                </h4>

                                <?php foreach ($brand->brandParams as $param) : ?>
                                    <?php
                                    $value = '';
                                    $special_value = '';
                                    if ($selected_brands) {
                                        if (in_array($param->id, array_keys($selected_brands))) {
                                            $value = $selected_brands[$param->id];
                                        }
                                    }?>

                                    <?php if (strpos($param->value, $label) !== false) {
                                        $custom_inner_label = explode(', ', $param->value);
                                            $inner_label =  $param->reg_description ? $param->reg_description :
                                                $custom_inner_label[1];
                                            ?>
                                            <?php echo Html::activeLabel($model, "brands[$param->id]", ['label' => $inner_label]) ?>
                                            <div class="units-dollar">
                                                <?php echo $form->field($model, "brands[$param->id]")->textInput(['placeholder' => Yii::t('app', 'Enter Price'), 'value' => $value])->label(false) ?>
                                            </div>
                                        <div class="units-dollar">
                                            <?php echo $form->field($model, "brand_special[$param->id]")->textInput(['placeholder' => Yii::t('app', 'Enter Special Price'), 'value' => $special_value])->label(false) ?>
                                        </div>
                                   <?php } ?>

                                <?php endforeach ?>
                                </div>
                            <?php endforeach ?>
                        <?php else : ?>
                            <?php foreach ($brand->brandParams as $param) : ?>
                                <?php
                                $value = '';
                                $special_value = '';
                                if ($selected_brands) {
                                    if (in_array($param->id, array_keys($selected_brands))) {
                                        $value = $selected_brands[$param->id];
                                    }
                                }?>
                                <div class="col-xs-6">
                                    <?php $label =  $param->reg_description ? $param->reg_description : (($param->value == 1 || !is_numeric($param->value)) ?
                                        ($param->value . (!is_numeric($param->value) ? (", ") : ' ') . Brand::getPer($brand->per) . ($brand->id == 38 ? ", " . Yii::t('app', '2 syringes per session') : '')) :
                                        ($param->value . " " . Inflector::pluralize(Brand::getPer($brand->per))) . ($brand->id == 38 ? ", " . Yii::t('app', '2 syringes per session') : '')); ?>
                                    <?php echo Html::activeLabel($model, "brands[$param->id]", ['label' => $label]) ?>
                                    <div class="units-dollar">
                                        <?php echo $form->field($model, "brands[$param->id]")->textInput(['placeholder' => Yii::t('app', 'Enter Price'), 'value' => $value])->label(false) ?>
                                    </div>
                                    <div class="units-dollar">
                                        <?php echo $form->field($model, "brand_special[$param->id]")->textInput(['placeholder' => Yii::t('app', 'Enter Special Price'), 'value' => $special_value])->label(false) ?>
                                    </div>
                                </div>
                            <?php endforeach ?>
                            <?php endif ?>
                    <?php endif ?>
                <?php endif ?>
            </div>
        </div>
    </div>

