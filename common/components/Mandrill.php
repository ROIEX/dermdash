<?php
/**
 * Created by PhpStorm.
 * User: rexit
 * Date: 4/28/2016
 * Time: 2:19 PM
 */

namespace common\components;


use Yii;

class Mandrill extends \Mandrill
{
    public $ch;

    public function __construct($apikey = null)
    {
        parent::__construct($apikey);
        //curl_setopt ($this->ch, CURLOPT_CAINFO, Yii::getAlias('@base') . "/cacert.pem");
    }
}