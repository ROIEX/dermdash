<?php
/* @var $this \yii\web\View */
/* @var $treatment \common\models\Treatment */

$items = function($type) {
    return \yii\helpers\ArrayHelper::map(\common\models\Brand::find()->where(['type'=>$type])->all(),'name','name');
};

?>
<h1><?= $treatment->name ?></h1>
<?php \yii\bootstrap\ActiveForm::begin() ?>

<div class="form-group field-comment">
    <div class="row">
        <div class="col-sm-8">
            <label class="control-label" for="fillers">1. Do you recommend any fillers?</label> <?= \yii\bootstrap\Html::dropDownList('recommend_fillers',null,array_merge(['No'=>'No'],$items(\common\models\Brand::TYPE_FILLER)),['id'=>'fillers']) ?><br/><br>
            If yes, what is the number of syringes you recommend? <?= \yii\bootstrap\Html::input('text','filler[syringes_number]',null,['class'=>'filler']) ?><br/><br>
            Cost: $ <?= \yii\bootstrap\Html::input('text','filler[cost]',null,['class'=>'filler']) ?><br/><br>
            <label class="control-label" for="neurotoxins">2. Do you recommend any Neurotoxins?</label> <?= \yii\bootstrap\Html::dropDownList('recommend_neurotoxins',null,array_merge(['No'=>'No'],$items(\common\models\Brand::TYPE_NEUROTOXIN)),['id'=>'neurotoxins']) ?>
            <br><br>
            If yes, what is the number of units you recommend? <?= \yii\bootstrap\Html::input('text','neurotoxin[units_number]',null,['class'=>'neurotoxin']) ?><br/><br>
            Cost: $ <?= \yii\bootstrap\Html::input('text','neurotoxin[cost]',null,['class'=>'neurotoxin']) ?><br/><br>
            <br><br>

        </div>
        <div class="col-sm-4">

        </div>
    </div>
    <label class="control-label" for="comment">3. Other recommendations, advice or counselling (optional):</label> <br>
    <?= \yii\bootstrap\Html::textarea('comment','',['id'=>'comment','cols'=>100,'rows'=>5,'prompt'=>'weqt']) ?><br><br>



    <?= \yii\bootstrap\Html::checkbox('recommend_arnica',false,['id'=>'recommend_arnica']) ?>
    <label class="control-label" for="recommend_arnica">Recommend Arnica tablets several days prior to procedure to avoid bruising.</label><br><br>

    <?= \yii\bootstrap\Html::checkbox('avoidings',false,['id'=>'avoidings']) ?>
    <label class="control-label" for="avoidings">Avoid Aspirin, and alcohol to reduce risk of bruising.</label> <br>
</div>
<?= \yii\helpers\Html::submitButton(Yii::t('app','Submit',['class'=>'btn btn-success'])) ?>
<?php \yii\bootstrap\ActiveForm::end(); ?>

<?php
$js = /** @lang JavaScript */
    <<<JS
    $('.filler').prop('disabled',true);
    $('.neurotoxin').prop('disabled',true);
$(document).on('change','select[name=recommend_fillers]',function(){
    var value = $(this).val();
    if (value != 'No') {
        $('.filler').prop('disabled',false)
    } else {
        $('.filler').prop('disabled',true)
        $('.filler').val('');
    }
})

$(document).on('change','select[name=recommend_neurotoxins]',function(){
    var value = $(this).val();
    if (value != 'No') {
        $('.neurotoxin').prop('disabled',false);
    } else {
        $('.neurotoxin').prop('disabled',true);
        $('.neurotoxin').val('');
    }
})
JS;
$this->registerJs($js);