<?php

use common\models\Payment;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\helpers\Html;
use common\components\dateformatter\FormatDate;

$url = Url::toRoute('/inquiry/view');
$js = <<<SCRP
$(document).on('click', '#get_note', function(){
    getChartNote('{$url}', $(this).data('note_id'));
     return false;
})
SCRP;
$this->registerJs($js);

?>
    <div class="col-xs-12">
        <h3 ><?php echo Yii::t('app', 'Chart notes:') ?></h3>

        <?php echo GridView::widget([
            'dataProvider' => $model,
            'columns' => [
                [
                    'label'=>Yii::t('app', 'Name'),
                    'value' => function($data) {
                        return $data->user->getPublicIdentity();
                    }
                ],
                [
                    'label' => Yii::t('app', 'Date'),
                    'format' => 'raw',
                    'value' => function($data) {
                        return Html::a(FormatDate::AmericanFormatFromTimestamp($data->created_at), '', ['id' => 'get_note', 'data'=>['note_id' => $data->id]]);
                    }
                ],
                [
                    'label' => Yii::t('app', 'Type'),
                    'value' => function($data) {
                        return $data->getInquiryCureType();
                    }
                ],
                [
                    'label' => Yii::t('app', 'Item'),
                    'value' => function($data) {
                        return $data->getInquiryItem();
                    }
                ],
                [
                    'label' => Yii::t('app', 'Purchase Status'),
                    'value' => function($data) {
                        if (isset($data->payment)) {
                            return Payment::getOfferStatus($data->payment->offer_status);
                        }
                        return Yii::t('app', 'None');
                    }
                ],
                [
                    'label' => Yii::t('app', 'Status'),
                    'value' => function($data) {
                        return $data->getInquiryStatus($data, true);
                    }
                ],
            ]
        ]); ?>
    </div>

    <div id="chart_note" class="col-xs-12">
        <?php echo $this->render('/inquiry/chart-note') ?>
    </div>

