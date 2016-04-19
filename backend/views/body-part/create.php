<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\BodyPart */

$this->title = Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Body Part',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Body Parts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="body-part-create">

    <?php echo $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
