<?php
/**
 * Created by PhpStorm.
 * User: rexit
 * Date: 11.01.16
 * Time: 17:45
 */

namespace frontend\modules\api\v1\resources;


use Yii;
use yii\web\ErrorHandler;
use yii\web\Response;

class ApiErrorHandler extends ErrorHandler
{
    /**
     * @param \Exception $exception
     */

    protected function renderException($exception)
    {
        if (Yii::$app->has('response')) {
            $response = Yii::$app->getResponse();
        } else {
            $response = new Response();
        }


        $response->data = $this->convertExceptionToArray($exception);
        $response->setStatusCode($this->getCode($exception));

        $response->send();
    }

    /**
     * @inheritdoc
     */

    protected function convertExceptionToArray($exception)
    {

        $arr = ['message' => $exception->getMessage(), 'code' => $this->getCode($exception)];
        return $arr;
    }

    private function getCode($exception)
    {
        if (empty($exception->statusCode)){
            $code = 500;
        } else {
            $code = $exception->statusCode;
        }
        return $code;
    }
}