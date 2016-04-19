<?php

use common\components\dateformatter\FormatDate;
use yii\helpers\Html;
use yii\helpers\Url;

?>


<div class="col-xs-6">
    <h3 ><?php echo Yii::t('app', 'Patients Basic Information:') ?></h3>

    <?php echo $model->getPublicIdentity() . ", " . $model->email ?>
    </br>

    <?php if (!empty($model->userProfile)) { ?>

         <?php echo isset($model->userProfile->address) ? $model->userProfile->address : '' ?>
         <br/>
         <?php echo $model->userProfile->city . ", " . $model->userProfile->state->name . ", " . $model->userProfile->zipcode ?>
         <br/>
         <?php echo isset($model->userProfile->phone) ? $model->userProfile->phone : '' ?>
         <br/>

        <?php if (Yii::$app->user->can('administrator')) { ?>

            <h4><?php echo Yii::t('app', 'Patient reward: {reward}', [
                    'reward' => $model->userProfile->reward ? $model->userProfile->reward : 0
                ]) ?><br/></h4>

        <?php } ?>

   <?php } ?>

        <?php if (Yii::$app->user->can('administrator') && $used_promo) { ?>
            <h3><?php echo Yii::t('app', 'Used promo codes:') ?></h3>

            <?php foreach($used_promo as $promo) { ?>

                <?php echo Yii::t('app', 'Promo code: {code}', [
                    'code' => Html::a($promo->promoCode->text, Url::toRoute(['promo/view', 'id' => $promo->promo_id]))
                ]) ?><br/>

                <?php echo Yii::t('app', 'Promo amount: {amount}', [
                    'amount' => $promo->promoCode->value
                ]) ?><br/>

                <?php echo Yii::t('app', 'Usage date: {date}', [
                    'date' => FormatDate::AmericanFormatFromTimestamp($promo->created_at)
                ]) ?><br/>

            <?php } ?>

       <?php }?>



</div>


