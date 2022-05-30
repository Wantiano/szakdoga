<?php

namespace App\Utils;

use Exception;

class ArrayUtil {

    public static function removeElementByIndex(array $arr, int $index) {
        if(count($arr) <= $index || $index < 0) {
            throw new Exception('Index out of array');
        }

        unset($arr[$index]);
        return array_values($arr);
    }

    public static function getRandomElement(array $arr) {
        return $arr[ArrayUtil::getRandomIndex($arr)];
    }

    public static function getRandomIndex(array $arr) {
        if(count($arr) == 0) {
            throw new Exception('Array is empty');
        }

        return array_rand($arr);
    }
}

?>