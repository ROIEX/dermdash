<?php
/* @var $dataProvider ArrayDataProvider */
/* @var $this View */
use common\models\MailchimpList;
use yii\data\ArrayDataProvider;
use yii\web\View;

?>
<p><?= \yii\helpers\Html::a(Yii::t('app','Create'),['create'],['class'=>'btn btn-success']) ?></p>
<?= \yii\grid\GridView::widget([
    'dataProvider'=>$dataProvider,
    'columns'=>[
        'id'=>[
            'attribute'=>'id',
            'value'=>function($model){
                /* @var $model MailchimpList */
                return \yii\bootstrap\Html::a($model['id'],['view','id'=>$model['id']]);
            },
            'format'=>'raw'
        ],
        'name',
        'subject',
        'visibility'=>[
            'attribute'=>'visibility',
            'value'=>function($model){
                /* @var $model MailchimpList */
                return (new MailchimpList())->getVisibility()[$model['visibility']];
            }
        ]
    ]
]) ?>

