<?php

use common\components\dateformatter\FormatDate;
use yii\helpers\Html;
use yii\helpers\Url;

$url = Url::toRoute('/inquiry/deny');
$js = <<<SCRP
$(document).on('click', '#deny-inquiry', function(){
    denyInquiry('{$url}', $(this).data('inquiry_id'), $(this).data('user_id'));
     return false;
})
SCRP;

$this->registerCss("

.gallery-item > img {
    width:200px;
}
");

?>

<?php if (isset($model)) { ?>

    <div class="col-xs-12">
        <?php if ($model->existingDoctorOffer) { ?>

            <h3><?php echo Yii::t('app', 'Your offer') ?></h3>

            <?php echo Yii::t('app', 'Offer price: {price}', [
                'price' => $model->existingDoctorOffer->price
            ])?><br/>

            <?php echo Yii::t('app', 'Offer date: {date}', [
                'date' => FormatDate::AmericanFormatFromTimestamp($model->existingDoctorOffer->created_at)
            ])?><br/>

            <?php if ($model->finalizedInquiry) { ?>

                <?php if($model->finalizedInquiry[0]->user_id == Yii::$app->user->id) {
                    echo Html::tag('div',  Yii::t('app', 'Purchased'), ['class' => 'text-success']);
                } else {
                    echo Html::tag('div', Yii::t('app', 'Not purchased'), ['class' => 'text-warning']);
                } ?><hr>

            <?php } ?>

            <?php } ?>
    </div>
        <div class="col-xs-12">
            <h3><?php echo Yii::t('app', 'Notes detail') ?></h3>

            <?php echo Yii::t('app', 'Category: {category}', [
                'category' => $model->getInquiryCureType()
            ]) ?><br/>

            <?php echo Yii::t('app', 'Item: {item}', [
                'item' => $model->getInquiryItem()
            ]) ?><br/>

            <?php echo Yii::t('app', 'Selected params:')?>
            <br/>

            <?php if ($model->getInquiryParams()) {
                foreach ($model->getInquiryParams() as $param) {
                    echo implode(', ', $param) . '<br/>';
                }
            }?>

            <?php if ($model->inquiryTreatments) {
                foreach ($model->inquiryTreatments as $treatment) {
                    if (!empty($treatment->severityParam)) {
                        echo $treatment->severityParam->severity->name . "</br>";
                    }

                }
            }

            ?>

            <?php echo Yii::t('app', 'Status: {status}', [
                'status' => $model->getInquiryStatus($model, true)
            ]) ?><br/><br/>

        </div><hr>

    <?php if (Yii::$app->user->can('administrator')) { ?>
        <div class="col-xs-12">
            <h4><?php echo Yii::t('app', 'Doctor offers') ?></h4>

            <?php if ($model->inquiryDoctorList) {

                foreach ($model->inquiryDoctorList as $offer) { ?>

                    <?php echo Yii::t('app', 'Doctor', [
                            'price' => $offer->price]) . ' :' . Html::a($offer->user->getPublicIdentity(), Url::toRoute(['doctor/view', 'id' => $offer->user->doctor->id]));
                    ?><br/>

                    <?php if ($offer->status == \common\models\InquiryDoctorList::STATUS_ANSWER_YES || $offer->status == \common\models\InquiryDoctorList::STATUS_FINALIZED) { ?>

                        <?php echo Yii::t('app', 'Offer price: {price}', [
                            'price' => $offer->price
                        ])?><br/>

                    <?php } ?>
                    <hr>

                <?php } ?>

            <?php } ?>

        </div>
    <?php } ?>

<?php } ?>

