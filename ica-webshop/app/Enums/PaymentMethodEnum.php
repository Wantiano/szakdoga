<?php

namespace App\Enums;

class PaymentMethodEnum {

    const PAYPAL = 'PAYPAL';
    const ON_DELIVERY = 'ON_DELIVERY';

    static function valueArray() {
        return [
            PaymentMethodEnum::PAYPAL,
            PaymentMethodEnum::ON_DELIVERY,
        ];
    }

    static function methodArray() {
        return [
            [
                'value' => PaymentMethodEnum::PAYPAL,
                'cost' => 0,
                'message' => 'PayPal - Online'
            ],
            [
                'value' => PaymentMethodEnum::ON_DELIVERY,
                'cost' => 450,
                'message' => 'Utánvétel - Készpénz, vagy bankkártya'
            ],
        ];
    }

    static function getMessage($value) {
        foreach (PaymentMethodEnum::methodArray() as $method) {
            if($method['value'] == $value)
                return $method['message'];
        }
        return null;
    }
    
    static function getCost($value) {
        foreach (PaymentMethodEnum::methodArray() as $method) {
            if($method['value'] == $value)
                return $method['cost'];
        }
        return null;
    }
}

?>