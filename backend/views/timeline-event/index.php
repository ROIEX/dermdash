<?php

use yii\helpers\Url;
use common\models\Doctor;
use common\models\User;
use common\models\Inquiry;
/**
 * Eugine Terentev <eugine@terentev.net>
 * @var $this \yii\web\View
 * @var $model \common\models\TimelineEvent
 * @var $dataProvider \yii\data\ActiveDataProvider
 */
$this->title = Yii::t('backend', 'Application timeline');
$icons = [
    'user'=>'<i class="fa fa-user bg-blue"></i>'
];
?>

<?php if (Yii::$app->user->can('administrator')) { ?>

    <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-aqua">
            <div class="inner">
                            <h3><?php echo Inquiry::getPendingInquiryCount() ?></h3>
                <p><?php echo Yii::t('app', 'Pending visits')?></p>
            </div>
            <div class="icon">
                <img src="<?php echo Yii::getAlias('@backendUrl') ?>/img/query.png"/>
            </div>
                    <a href="<?php echo Url::toRoute(['/inquiry/index', 'status' => Inquiry::STATUS_PENDING])?>" class="small-box-footer"><?php echo Yii::t('app', 'More info')?> <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>

    <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-green">
            <div class="inner">
                <h3>
                    <?php echo Doctor::getDoctorCount()?>
                </h3>
                <p><?php echo Yii::t('app', 'Doctor Registration')?></p>
            </div>
            <div class="icon">
                <img src="<?php echo Yii::getAlias('@backendUrl') ?>/img/doctor_registration.png"/>
            </div>
            <a href="<?php echo Url::toRoute(['/doctor/index'])?>" class="small-box-footer"><?php echo Yii::t('app', 'More info')?> <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>


    <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-yellow">
            <div class="inner">
                <h3><?php echo User::patientCount(); ?></h3>
                <p><?php echo Yii::t('app', 'Patient Registration')?></p>
            </div>
            <div class="icon">
                <img src="<?php echo Yii::getAlias('@backendUrl') ?>/img/patient_registrations.png"/>
            </div>
            <a href="<?php echo Url::toRoute(['/patient/index'])?>" class="small-box-footer"><?php echo Yii::t('app', 'More info')?> <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>

    <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-red">
            <div class="inner">
                            <h3><?php echo Inquiry::getFinalizedInquiryCount() ?></h3>
                <p><?php echo Yii::t('app', 'Completed visits')?></p>
            </div>
            <div class="icon">
                <img src="<?php echo Yii::getAlias('@backendUrl') ?>/img/unique_visitors.png"/>
            </div>
                    <a href="<?php echo Url::toRoute(['/inquiry/index', 'status' => Inquiry::STATUS_COMPLETED])?>" class="small-box-footer"><?php echo Yii::t('app', 'More info')?> <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>

<?php } ?>

<?php \yii\widgets\Pjax::begin() ?>
<div class="row">
    <div class="col-md-12">
        <?php if ($dataProvider->count > 0): ?>
            <ul class="timeline">
                <?php foreach($dataProvider->getModels() as $model): ?>
                    <?php if(!isset($date) || $date != Yii::$app->formatter->asDate($model->created_at)): ?>
                        <!-- timeline time label -->
                        <li class="time-label">
                            <span class="bg-blue">
                                <?php echo Yii::$app->formatter->asDate($model->created_at) ?>
                            </span>
                        </li>
                        <?php $date = Yii::$app->formatter->asDate($model->created_at) ?>
                    <?php endif; ?>
                    <li>
                        <?php
                            try {
                                $viewFile = sprintf('%s/%s', $model->category, $model->event);
                                echo $this->render($viewFile, ['model' => $model]);
                            } catch (\yii\base\InvalidParamException $e) {
                                echo $this->render('_item', ['model' => $model]);
                            }
                        ?>
                    </li>
                <?php endforeach; ?>
                <li>
                    <i class="fa fa-clock-o">
                    </i>
                </li>
            </ul>
        <?php else: ?>
            <?php echo Yii::t('backend', 'No events found') ?>
        <?php endif; ?>
    </div>
    <div class="col-md-12 text-center">
        <?php echo \yii\widgets\LinkPager::widget([
            'pagination'=>$dataProvider->pagination,
            'options' => ['class' => 'pagination']
        ]) ?>
    </div>
</div>
<?php \yii\widgets\Pjax::end() ?>

