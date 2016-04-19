<?php
/**
 * Created by PhpStorm.
 * User: rexit
 * Date: 28.12.15
 * Time: 20:06
 */

namespace frontend\modules\api\v1\resources;


class Patient extends \common\models\UserProfile
{
    public function fields()
    {
        return ['user_id', 'firstname', 'lastname', 'date_of_birth', 'city', 'state_id', 'gender'];
    }

    public function extraFields()
    {
        return ['user'];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}