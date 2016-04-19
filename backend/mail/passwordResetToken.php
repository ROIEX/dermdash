<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['/sign-in/reset-password', 'token' => $user->password_reset_token]);
?>

<?php echo Yii::t('app', 'Hello') . " " . Html::encode($user->username) . ", " . Yii::t('app', 'Follow the link below to reset your password:')?><br/>
<?php echo Html::a(Html::encode($resetLink), $resetLink) ?>
