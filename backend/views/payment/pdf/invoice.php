<?php /** @var \common\components\Invoice $invoice */
use yii\bootstrap\BootstrapAsset;

/* @var $this yii\web\View */
?>

<div class="container">
    <div class="row">
        <div class="col-xs-6">
            <h1>
                <img src="logo.png">
                Logo here
            </h1>
        </div>
        <div class="col-xs-6">
            <h1><small>Invoice #001</small></h1>
        </div>
    </div>

    <div class="row">

        <div class="col-xs-6">
            <?php echo Yii::t('app', 'Company Name :  {company_name}', ['company_name' => $invoice->company_name]) ?>
        </div>

        <div class="col-xs-6">
            <?php echo Yii::t('app', 'Address :  {address}', ['address' => $invoice->address]) ?>
        </div>

        <div class="col-xs-6">
            <?php echo Yii::t('app', 'Address :  {address}', ['address' => $invoice->location]) ?>
        </div>

        <div class="col-xs-6">
            <?php echo Yii::t('app', 'Phone :  {address}', ['address' => $invoice->phone]) ?>
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
            <?php if ($invoice->treatment_array) : ?>
                <?php foreach ($invoice->treatment_array as $treatment) : ?>
                    <tr>
                        <td>1</td>
                        <td><?php echo \common\components\dateformatter\FormatDate::AmericanFormatFromTimestamp($invoice->purchase_date) ?></td>
                        <td class="text-right"><?php echo $treatment['param'] . ' ( ' . $treatment['used_brands'] . ' items )'?></td>
                        <td class="text-right"><?php echo $treatment['price']?></td>
                        <td class="text-right"><?php echo $invoice->fee . ' %'?></td>
                        <td class="text-right"><?php echo($treatment['price'] * (1 - $invoice->fee / 100) ) ?></td>
                    </tr>
                <?php endforeach?>
            <?php endif?>

            <?php if ($invoice->brand_array) : ?>
                <?php foreach ($invoice->brand_array as $brand) : ?>
                    <tr>
                        <td>1</td>
                        <td><?php echo \common\components\dateformatter\FormatDate::AmericanFormatFromTimestamp($invoice->purchase_date) ?></td>
                        <td class="text-right"><?php echo $brand['param'] . ' ( ' . $brand['used_brands'] . ' items )'?></td>
                        <td class="text-right"><?php echo $brand['price'] ?></td>
                        <td class="text-right"><?php echo $invoice->fee . ' %'?></td>
                        <td class="text-right"><?php echo ($brand['price'] * (1 - $invoice->fee / 100) )?></td>
                    </tr>
                <?php endforeach?>
            <?php endif?>

            </tbody>
        </table>
    </div>

    <div class="row text-right">
        <div class="col-xs-4 col-xs-offset-8">
            <p>
                <strong>
                   <?php echo Yii::t('app', 'Total : {total} $', ['total' => $invoice->net_total/100])?><br>
                </strong>
            </p>
        </div>
    </div>

</div>



