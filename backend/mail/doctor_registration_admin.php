<?php

use common\models\UserProfile;
use common\components\dateformatter\FormatDate;

?>

<?php echo Yii::t('app', 'New doctor is Registered with DermDash.')?> <br/><br/>
<?php echo Yii::t('app', 'His/her email id (User Name) is')?> "<?php echo $doctor->user->email ?>"<br>
<?php echo Yii::t('app', 'First Name')?>: "<?php echo $doctor->profile->firstname ?>"<br>
<?php echo Yii::t('app', 'Last Name')?>: "<?php echo $doctor->profile->lastname ?>"<br>
<?php echo Yii::t('app', 'Gender')?>:" <?php echo UserProfile::getGender($doctor->profile->gender) ?>"<br>
<?php echo Yii::t('app', 'Date of birth')?>: "<?php echo FormatDate::AmericanFormat($doctor->profile->date_of_birth); ?>"<br>
<?php echo Yii::t('app', 'Current Clinic\'s Name')?>: "<?php echo $doctor->clinic ?>"<br>
<?php echo Yii::t('app', 'Contact no')?>: "<?php echo $doctor->profile->phone ?>"<br>
<?php echo Yii::t('app', 'Medical License')?> # : "<?php echo $doctor->license ?>"<br>

<?php echo Yii::t('app', 'Thank you so much for registering with DermDash.');?>
