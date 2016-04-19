<?php
/* @var $this \yii\web\View */
/* @var $treatment \common\models\Treatment */
?>
<h1><?= $treatment->name ?></h1>
<?php \yii\bootstrap\ActiveForm::begin() ?>
<table class='table table-bordered'>
    <thead>
    <tr>
        <th><?= Yii::t('app','Area') ?></th>
        <th><?= Yii::t('app','Brands') ?></th>
        <th><?= Yii::t('app','# Sessions') ?></th>
        <th><?= Yii::t('app','Cost') ?></th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td><?= Yii::t('app','Face') ?></td>
        <td><?= \yii\bootstrap\Html::input('text','face[brands]') ?></td>
        <td><?= \yii\bootstrap\Html::input('text','face[sessions]') ?></td>
        <td><?= \yii\bootstrap\Html::input('text','face[cost]') ?></td>
    </tr>
    <tr>
        <td><?= Yii::t('app','Neck') ?></td>
        <td><?= \yii\bootstrap\Html::input('text','neck[brands]') ?></td>
        <td><?= \yii\bootstrap\Html::input('text','neck[sessions]') ?></td>
        <td><?= \yii\bootstrap\Html::input('text','neck[cost]') ?></td>
    </tr>
    <tr>
        <td><?= Yii::t('app','Chest') ?></td>
        <td><?= \yii\bootstrap\Html::input('text','chest[brands]') ?></td>
        <td><?= \yii\bootstrap\Html::input('text','chest[sessions]') ?></td>
        <td><?= \yii\bootstrap\Html::input('text','chest[cost]') ?></td>
    </tr>
    <tr>
        <td><?= Yii::t('app','Other') ?></td>
        <td><?= \yii\bootstrap\Html::input('text','other[brands]') ?></td>
        <td><?= \yii\bootstrap\Html::input('text','other[sessions]') ?></td>
        <td><?= \yii\bootstrap\Html::input('text','other[cost]') ?></td>
    </tr>
    <tr>
        <td></td>
        <td><?= \yii\bootstrap\Html::input('text','other-2[brands]') ?></td>
        <td><?= \yii\bootstrap\Html::input('text','other-2[sessions]') ?></td>
        <td><?= \yii\bootstrap\Html::input('text','other-2[cost]') ?></td>
    </tr>
    </tbody>
</table>

<div class="form-group field-comment">
    <label class="control-label" for="comment"><?= Yii::t('app','Comment') ?></label> <br>
    <?= \yii\bootstrap\Html::textarea('comment','',['id'=>'comment','cols'=>100,'rows'=>5]) ?>
</div>
<?= \yii\helpers\Html::submitButton(Yii::t('app','Submit',['class'=>'btn btn-success'])) ?>
<?php \yii\bootstrap\ActiveForm::end(); ?>
