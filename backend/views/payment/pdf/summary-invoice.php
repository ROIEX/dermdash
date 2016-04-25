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
                <div>
                    <?php echo Yii::t('app', 'Company Name :  {company_name}', ['company_name' => $invoice['company_name']]) ?>
                </div>
                <div>
                    <?php echo Yii::t('app', 'Address :  {address}', ['address' => $invoice['address']]) ?>
                </div>
                <div>
                    <?php echo Yii::t('app', 'Address :  {address}', ['address' => $invoice['location']]) ?>
                </div>
                <div>
                    <?php echo Yii::t('app', 'Phone :  {phone}', ['phone' => $invoice['phone']]) ?>
                </div>
            </div>

            <div class="col-xs-4">
                <div>
                    <?php echo Yii::t('app', 'Company Name :  {company_name}', ['company_name' => $invoice['doctor_clinic']]) ?>
                </div>
                <div>
                    <?php echo Yii::t('app', 'Address :  {address}', ['address' => $invoice['doctor_location']]) ?>
                </div>
                <div>
                    <?php echo Yii::t('app', 'Phone :  {phone}', ['phone' => $invoice['doctor_phone']]) ?>
                </div>
            </div>
    </div>

    <!-- / end client details section -->
    <div class="row">
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>
                    <h4><?php echo Yii::t('app', 'Invoice number')?></h4>
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
                    <tr>
                        <td><?php echo $item['invoice_number']?></td>
                        <td class="text-right"><?php echo \common\components\dateformatter\FormatDate::AmericanFormatFromTimestamp($item['purchase_date'], true)?></td>
                        <td class="text-right"><?php echo $item['param'] . (!empty($item['used_brands']) ? ' ( ' . $item['used_brands']  .' items )' : '')?></td>
                        <td class="text-right"><?php echo '$ ' . round($item['price'], 2) ?></td>
                        <td class="text-right"><?php echo $invoice['fee'] . ' %'?></td>
                        <td class="text-right"><?php echo '$ ' . round($item['price'] * (1 - $invoice['fee'] / 100) )?></td>
                    </tr>
                <?php endforeach?>
            </tbody>
        </table>
    </div>
    <div class="row text-right">
        <div class="col-xs-4 col-xs-offset-8">
            <p>
                <strong>
                   <?php echo Yii::t('app', 'Total : {total} $', ['total' =>  $invoice['net_total'] ])?><br>
                </strong>
            </p>
        </div>
    </div>
</div>



