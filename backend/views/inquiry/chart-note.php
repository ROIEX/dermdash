<?php

use common\components\dateformatter\FormatDate;
use yii\helpers\Html;
use yii\helpers\Url;
use common\models\Inquiry;
use yii\helpers\BaseInflector;
?>
<?php
/** @var Inquiry $model */
if (isset($model)) { ?>
    <?php if ($offers) : ?>
        <?php foreach($offers as $offer) : ?>
        <div class="well">
            <h3><?php foreach ($offer['data'] as $offer_item): ?>

                    <?php if(Yii::$app->user->can('administrator')) : ?>
                        <p><?php echo Yii::t('app', 'Doctor: ') . $offer['doctor'] ?></p>
                        <p><?php echo Yii::t('app', 'Clinic: ') . $offer['clinic'] ?></p>
                    <?php endif ?>

                    <?php if (isset($offer_item['brand'])) : ?>
                        <p><?php echo Yii::t('app', 'Item: ') . $offer_item['brand'] ?></p>

                        <?php if (is_numeric((int)$offer_item['param_value'])) : ?>

                            <p><?php echo (int)$offer_item['param_value'] > 1 ?
                                    (BaseInflector::pluralize($offer_item['param_name']) . ': ' . $offer_item['param_value']) :
                                    ($offer_item['param_name'] . ' ' . $offer_item['param_value']) ?></p>

                        <?php else : ?>
                            <p> <?php echo $offer_item['param_name'] . ': ' . $offer_item['param_value'] ; ?></p>

                        <?php endif ?>

                        <p><?php echo Yii::t('app', 'Cost: ') . $offer_item['price'] ?></p>

                    <? else : ?>
                        <p><?php echo Yii::t('app', 'Treatment: {item}', ['item' => $model->getInquiryItem()]) ?></p>
                        <p><?php echo Yii::t('app', 'Area: ') . $offer_item['param'] ?></p>
                        <?php if($model->getInquiryItem() != $offer_item['procedure_name']) : ?>
                            <p><?php echo Yii::t('app', 'Used brand: ') . $offer_item['procedure_name'] ?></p>
                        <?php endif ?>

                        <p><?php echo (int)$offer_item['amount'] > 1 ?
                                (BaseInflector::pluralize($offer_item['param_name']) . ': ' . $offer_item['amount']) :
                                ($offer_item['param_name'] . ': ' . $offer_item['amount']) ?></p>
                        <p><?php echo Yii::t('app', 'Cost: ') . $offer_item['price'] ?></p>

                    <? endif ?>
                    <p><?php echo Yii::t('app', 'Status: {status}', ['status' => $model->getInquiryStatus($model, true)]) ?></p>

                <?php endforeach ?></h3>


        </div>
        <?php endforeach ?>
    <?php endif?>

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

