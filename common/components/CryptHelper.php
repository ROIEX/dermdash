<?php

namespace common\components;


class CryptHelper
{
    /**
     * @param $string
     * @return mixed
     */
    public static function encrypt($string)
    {
        return \Yii::$app->encrypter->encrypt($string);
    }


    /**
     * @param $string
     * @return mixed
     */
    public static function decrypt($string)
    {
        return \Yii::$app->encrypter->decrypt($string);
    }
}