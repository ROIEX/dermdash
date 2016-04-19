<?php
/**
 * Created by PhpStorm.
 * User: rexit
 * Date: 08.12.15
 * Time: 12:48
 */

namespace frontend\modules\api\v1\resources;

use common\models\User;
use Yii;

class Login extends User
{
    public function fields()
    {
        return ['id', 'username', 'created_at', 'access_token'];
    }
}