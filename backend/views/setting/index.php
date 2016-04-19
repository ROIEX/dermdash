
<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Settings');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="col-xs-12">

    <div class="row">
        <?php echo GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                'name',
                'value',
                'description',
                ['class' => 'yii\grid\ActionColumn', 'template' => '{view} {update}'],
            ],
        ]); ?>


        <h3><?php echo Yii::t('app', 'Severity levels')?></h3>

        <p>
            <?php echo Html::a(Yii::t('app', 'Create {modelClass}', [
                'modelClass' => 'Severity',
            ]), ['/severity/create'], ['class' => 'btn btn-success']) ?>
        </p>

        <?php echo GridView::widget([
            'dataProvider' => $severityProvider,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                'name',
                [
                    'label'=> Yii::t('app', 'Status'),
                    'value' =>function($data){
                        return common\components\StatusHelper::getStatus($data->status);
                    }
                ],

                ['class' => 'yii\grid\ActionColumn',
                    'controller' => 'severity',
                ],
            ],
        ]); ?>

        <h3><?php echo Yii::t('app', 'Intensity')?></h3>

        <p>
            <?php echo Html::a(Yii::t('app', 'Create {modelClass}', [
                'modelClass' => 'Intensity',
            ]), ['/intensity/create'], ['class' => 'btn btn-success']) ?>
        </p>

        <?php echo GridView::widget([
            'dataProvider' => $intensityProvider,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                'name',
                ['class' => 'yii\grid\ActionColumn', 'controller' => 'intensity'],
            ],
        ]); ?>
    </div>
