<?php

use yii\helpers\Html;
use yii\helpers\Url;
use fedemotta\datatables\DataTables;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Treatment Settings');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="col-xs-12">
    <p>
        <?php echo Html::a(Yii::t('app', 'Create {modelClass}', [
            'modelClass' => 'Treatment category',
        ]), ['create-category'], ['class' => 'btn btn-success']) ?>

    </p>
    <?php echo DataTables::widget([
        'dataProvider' => $category_list,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'name',
            ['class' => 'yii\grid\ActionColumn',
                'template'=>'{delete}',
                'buttons'=>[
                    'delete' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', Url::toRoute(['treatment-settings/category-delete', 'id' => $model->id]), [
                            'title' => Yii::t('yii', 'Delete'),
                        ]);
                    },
                ]
            ],
        ],
    ]); ?>
</div>

<div class="col-xs-12">
    <p>
        <?php echo Html::a(Yii::t('app', 'Create {modelClass}', [
            'modelClass' => 'Treatment type',
        ]), ['create-type'], ['class' => 'btn btn-success']) ?>

    </p>
    <?php echo DataTables::widget([
        'dataProvider' => $type_list,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'name',
            ['class' => 'yii\grid\ActionColumn',
                'template'=>'{delete}',
                'buttons'=>[
                    'delete' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', Url::toRoute(['treatment-settings/type-delete', 'id' => $model->id]), [
                            'title' => Yii::t('yii', 'Delete'),
                        ]);
                    },
                ]
            ],
        ],
    ]); ?>
</div>

<div class="col-xs-12">
    <p>
        <?php echo Html::a(Yii::t('app', 'Create {modelClass}', [
            'modelClass' => 'Treatment type count',
        ]), ['create-type-count'], ['class' => 'btn btn-success']) ?>

    </p>
    <?php echo DataTables::widget([
        'dataProvider' => $type_count_list,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'value',
            ['class' => 'yii\grid\ActionColumn',
                'template'=>'{delete}',
                'buttons'=>[
                    'delete' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', Url::toRoute(['treatment-settings/count-delete', 'id' => $model->id]), [
                            'title' => Yii::t('yii', 'Delete'),
                        ]);

                    },
                ]
            ],
        ],
    ]); ?>
</div>

