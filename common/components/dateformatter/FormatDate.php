<?php

namespace common\components\dateformatter;

use Yii;

class FormatDate
{
    public static function AmericanFormat($date)
    {
        return date('m/d/Y',strtotime($date));
    }

    public static function AmericanFormatFromTimestamp($date, $no_time = false)
    {
        $date = Yii::$app->formatter->asDatetime($date);

        if ($no_time) {
            return date('l jS \of F',strtotime($date));
        } else {
            return date('m/d/Y H:i:s',strtotime($date));
        }
    }
}