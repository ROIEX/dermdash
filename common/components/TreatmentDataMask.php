<?php
/**
 * Created by PhpStorm.
 * User: rexit
 * Date: 15.01.16
 * Time: 15:52
 */

namespace common\components;


class TreatmentDataMask
{
    //Bit masks
    const BODY_PART = 1 << 0;
    const PHOTO = 1 << 1;
    const RANDOM_ATT = 1 << 2;
    const RANDOM_PHOTO = 1 << 3;

    /**
     * Returns values of bit masks as array.
     * @return array
     */
    public static function getData()
    {
        $mask = self::BODY_PART | self::RANDOM_PHOTO;
        $bitArray = array_reverse(str_split(base_convert($mask, 10, 2)));
        $return = [];
        foreach($bitArray as $key => $value) {
            if($value) {
                $return[] = $key;
            }
        }
        return self::getConstAsArray($return);
    }

    /**
     * @param $data
     * @param int $endIndex
     * @return array
     */
    public static function getConstAsArray($data, $endIndex = 4)
    {
        $array = [];
        $index = 0;
        for ($counter = 1; $index < $endIndex; $counter*=2) {
            if (in_array($index, $data) && in_array($counter,self::getArrayBits())) {
                $array[] = $counter;
            }
            $index++;
        }
        return $array;
    }

    /**
     * @return array
     */
    public static function getArrayBits()
    {
        return [self::BODY_PART,self::PHOTO,self::RANDOM_ATT,self::RANDOM_PHOTO];
    }

}