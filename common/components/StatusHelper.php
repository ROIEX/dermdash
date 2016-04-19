<?php
/**
 * Created by PhpStorm.
 * User: rexit
 * Date: 15.01.16
 * Time: 15:52
 */

namespace common\components;

use Yii;


class StatusHelper
{
    const STATUS_NOT_ACTIVE = 0;
    const STATUS_ACTIVE = 1;

    public static function getStatusArray()
    {
        $array = [
            self::STATUS_ACTIVE => Yii::t('app', 'Active'),
            self::STATUS_NOT_ACTIVE => Yii::t('app', 'Not active'),
        ];
        return $array;
    }

    public static function getStatus($status)
    {
        $array = self::getStatusArray();
        return $array[$status];
    }
}