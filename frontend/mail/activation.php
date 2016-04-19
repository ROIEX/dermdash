<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */

$activateLink = Yii::$app->urlManager->createAbsoluteUrl(['/user/sign-in/activate', 'token' => $user->activation_token]);
?>

Hello <?php echo Html::encode($user->getPublicIdentity()) ?>,

Follow the link below to activate your account:

<?php echo Html::a(Html::encode($activateLink), $activateLink) ?>
