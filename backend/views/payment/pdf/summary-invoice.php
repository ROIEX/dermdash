<?php
/** @var \common\components\Invoice $invoice */
/* @var $this yii\web\View */
$total_price = 0;


?>

<div class="container">
    <div class="row">
        <div class="col-xs-6">
            <h1>
                <img src="<?php echo Yii::getAlias('@backendUrl') . '/img/dermdash-logo-website.jpg'?>">
            </h1>
        </div>
    </div>

    <div class="row">

        <div class="col-xs-6">
            <?php echo Yii::t('app', 'Company Name :  {company_name}', ['company_name' => $invoice['company_name']]) ?>
        </div>

        <div class="col-xs-6">
            <?php echo Yii::t('app', 'Address :  {address}', ['address' => $invoice['address']]) ?>
        </div>

        <div class="col-xs-6">
            <?php echo Yii::t('app', 'Address :  {address}', ['address' => $invoice['location']]) ?>
        </div>

        <div class="col-xs-6">
            <?php echo Yii::t('app', 'Phone :  {phone}', ['phone' => $invoice['phone']]) ?>
        </div>

    </div>

    <!-- / end client details section -->
    <div class="row">
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>
                    <h4><?php echo Yii::t('app', 'Item Number')?></h4>
                </th>
                <th>
                    <h4><?php echo Yii::t('app', 'Date of Purchase')?></h4>
                </th>
                <th>
                    <h4><?php echo Yii::t('app', 'Item Description')?></h4>
                </th>
                <th>
                    <h4><?php echo Yii::t('app', 'Total Price')?></h4>
                </th>
                <th>
                    <h4><?php echo Yii::t('app', 'Fees')?></h4>
                </th>
                <th>
                    <h4><?php echo Yii::t('app', 'Net Total')?></h4>
                </th>
            </tr>
            </thead>
            <tbody>

                <?php foreach ($invoice['items'] as $item) : ?>
                    <?php $total_price += $item['price']?>
                    <tr>
                        <td>1</td>
                        <td class="text-right"><?php echo \common\components\dateformatter\FormatDate::AmericanFormatFromTimestamp($item['purchase_date'])?></td>
                        <td class="text-right"><?php echo $item['param'] . ' ( ' . $item['used_brands'] . ' items )'?></td>
                        <td class="text-right"><?php echo '$ ' . $item['price'] ?></td>
                        <td class="text-right"><?php echo $invoice['fee'] . ' %'?></td>
                        <td class="text-right"><?php echo '$ ' . ($item['price'] * (1 - $invoice['fee'] / 100) )?></td>
                    </tr>
                <?php endforeach?>
            </tbody>
        </table>
    </div>

    <div class="row text-right">
        <div class="col-xs-4 col-xs-offset-8">
            <p>
                <strong>
                   <?php echo Yii::t('app', 'Total : {total} $', ['total' =>  $total_price * (1 - $invoice['fee'] / 100)])?><br>
                </strong>
            </p>
        </div>
    </div>

</div>



