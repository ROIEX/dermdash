<?php

namespace frontend\modules\api\v1;

use frontend\modules\api\v1\resources\ApiErrorHandler;
use Yii;
use yii\web\Response;

class Module extends \frontend\modules\api\Module
{
    public $controllerNamespace = 'frontend\modules\api\v1\controllers';

    public function init()
    {
        parent::init();
        if (Yii::$app->response->format == Response::FORMAT_HTML) {
            Yii::$app->response->format = 'json';
        }
        Yii::$app->user->identityClass = 'frontend\modules\api\v1\models\ApiUserIdentity';
        Yii::$app->user->enableSession = false;
        Yii::$app->user->loginUrl = null;
        //\Yii::$app->user->enableAutoLogin = false;
        //$handler = new ApiErrorHandler;
        //\Yii::$app->set('errorHandler', $handler);
       // $handler->register();
        Yii::warning(json_encode($_REQUEST,JSON_PRETTY_PRINT),'request_log');
    }
}
