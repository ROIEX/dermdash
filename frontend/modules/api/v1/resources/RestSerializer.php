<?php
/**
 * Created by PhpStorm.
 * User: rexit
 * Date: 08.12.15
 * Time: 18:30
 */

namespace frontend\modules\api\v1\resources;


use yii\base\Arrayable;
use yii\base\Model;
use yii\data\DataProviderInterface;
use yii\rest\Serializer;

class RestSerializer extends Serializer
{
    /**
     * Envelope single model result
     * @var string
     */
    public $singleEnvelope = false;

    /**
     * Serializes the validation errors in a model.
     * @param Model $model
     * @return array the array representation of the errors
     */
    public function serializeModelErrors($model)
    {
        return ModelError::get($model);
    }


}