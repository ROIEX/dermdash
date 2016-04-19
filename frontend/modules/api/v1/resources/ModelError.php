<?php
/**
 * Created by PhpStorm.
 * User: rexit
 * Date: 13.01.16
 * Time: 20:38
 */

namespace frontend\modules\api\v1\resources;


use yii\db\ActiveRecord;

class ModelError
{
    const ONE_ERROR = 1;

    public static function get($model)
    {
        /* @var $model ActiveRecord */
        if (!empty($model->getErrors())) {
            \Yii::$app->response->setStatusCode(422, 'Data Validation Failed.');
            $errors = [];
            foreach ($model->firstErrors as $error) {
                $errors[] = $error;
            }
            if (count($errors) > self::ONE_ERROR) {
                return [
                    'messages'=> $errors
                ];
            }
            return [
                'message'=> array_shift($errors)
            ];
        }
        return [];
    }
}