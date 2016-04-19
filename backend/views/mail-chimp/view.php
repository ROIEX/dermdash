<?php
use common\models\MailchimpList;
use yii\base\View;
/* @var $this View */
/* @var $model MailchimpList */

?>

<p>
    <?= \yii\bootstrap\Html::a(Yii::t('app','Update'),['update','id'=>$model->id],['class'=>'btn btn-primary']) ?>
    <?= \yii\bootstrap\Html::a(Yii::t('app','Import All Users'),['import','list_id'=>$model->id],['class'=>'btn btn-primary']) ?>
    <?= \yii\bootstrap\Html::a(Yii::t('app','Delete'),['delete','id'=>$model->id],['class'=>'btn btn-danger']) ?>
</p>

<?= \yii\widgets\DetailView::widget([
    'model'=>$model,
    'attributes'=>[
        'id',
        'name',
        'company',
        'address1',
        'address2',
        'city',
        'state',
        'zip',
        'country',
        'phone',
        'from_name',
        'from_email',
        'subject',
        'language',
        'notify_on_subscribe',
        'notify_on_unsubscribe',
        'permission_reminder',
        'email_type_option',
        'use_archive_bar',
        'visibility'=>[
            'attribute'=>'visibility',
            'value'=>$model->getVisibility()[$model->visibility]
        ],
    ]
]) ?>
