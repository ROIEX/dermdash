<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use fedemotta\datatables\DataTables;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
$url = Url::toRoute('/state/status');
$js = <<<SCRP
$(document).on('click', ".change_status", function(){
    updateStateStatus('{$url}', $(this).data('id'));
    return false;
})
SCRP;
$this->registerJs($js);
$this->title = Yii::t('app', 'States');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="state-index">
    <?php Pjax::begin(['id' => 'state_list']); ?>
        <?php echo DataTables::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                'name',
                'short_name',
                [
                    'label'=>'Status',
                    'format' => 'raw',
                    'value' =>function($data){
                        return  Html::a(
                            $data->status == \common\models\State::STATUS_ACTIVE ? Yii::t('app', 'Enabled') : Yii::t('app', 'Disabled'),
                            '', ['class' => 'change_status','data'=>['id' => $data->id]]);
                    }
                ],
            ],
        ]); ?>
    <?php Pjax::end(); ?>

</div>
