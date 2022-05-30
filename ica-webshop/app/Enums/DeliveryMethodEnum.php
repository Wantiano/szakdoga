<?php

namespace App\Enums;

class DeliveryMethodEnum {

    const PERSONAL = 'PERSONAL';
    const DPD = 'DPD';
    const GLS = 'GLS';

    static function valueArray() {
        return [
            DeliveryMethodEnum::PERSONAL,
            DeliveryMethodEnum::DPD,
            DeliveryMethodEnum::GLS,
        ];
    }

    static function methodArray() {
        return [
            [
                'value' => DeliveryMethodEnum::PERSONAL,
                'cost' => 0,
                'message' => 'Személyes átvétel üzletünkben'
            ],
            [
                'value' => DeliveryMethodEnum::DPD,
                'cost' => 990,
                'message' => 'Futárral DPD'
            ],
            [
                 'value' => DeliveryMethodEnum::GLS,
                 'cost' => 990,
                 'message' => 'Futárral GLS'
            ],
        ];
    }

    static function getMessage($value) {
        foreach (DeliveryMethodEnum::methodArray() as $method) {
            if($method['value'] == $value)
                return $method['message'];
        }
        return null;
    }

    static function getCost($value) {
        foreach (DeliveryMethodEnum::methodArray() as $method) {
            if($method['value'] == $value)
                return $method['cost'];
        }
        return null;
    }
}

?>