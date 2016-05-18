<?php

?>

<div class="row">
    <div class="col-xs-6">
        <h1>
            <img src="<?php echo Yii::getAlias('@backendUrl') . '/img/dermdash-logo-website.jpg' ?>">
        </h1>
    </div>
</div>
<div class="container">
    <div class="row">
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>
                    <h4><?php echo Yii::t('app', 'Treatment') ?></h4>
                </th>
                <th>
                    <h4><?php echo Yii::t('app', 'Value') ?></h4>
                </th>
                <th>
                    <h4><?php echo Yii::t('app', 'Price') ?></h4>
                </th>
            </tr>
            </thead>
            <tbody>
                <?php echo $this->render('form/_form-treatment', [
                    'treatments' => $treatments,
                ]) ?>
            </tbody>
        </table>
    </div>
    <br/>
    <div class="row">
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>
                    <h4><?php echo Yii::t('app', 'Brand') ?></h4>
                </th>
                <th>
                    <h4><?php echo Yii::t('app', 'Value') ?></h4>
                </th>
                <th>
                    <h4><?php echo Yii::t('app', 'Price') ?></h4>
                </th>
            </tr>
            </thead>
            <tbody>
            <?php echo $this->render('form/_form-brand', [
                'brands' => $brands,
            ]) ?>
            </tbody>
        </table>
    </div>
</div>



