<?php

namespace App\Enums;

class StatusEnum {

    const INCOMPLETE = 'INCOMPLETE';
    const PENDING = 'PENDING';
    const ACCEPTED = 'ACCEPTED';
    const REJECTED = 'REJECTED';
    const SENT = 'SENT';
    const ARRIVED = 'ARRIVED';
    const CANCELED = 'CANCELED';

    static function valueArray() {
        return [
            StatusEnum::INCOMPLETE,
            StatusEnum::PENDING, StatusEnum::ACCEPTED, 
            StatusEnum::REJECTED, StatusEnum::SENT, 
            StatusEnum::ARRIVED, StatusEnum::CANCELED
        ];
    }

    static function statusArray() {
        return [
            [
                'value' => StatusEnum::INCOMPLETE,
                'message' => 'Befejezetlen'
            ],
            [
                'value' => StatusEnum::PENDING,
                'message' => 'Nem feldolgozott'
            ],
            [
                'value' => StatusEnum::ACCEPTED,
                'message' => 'Elfogadott'
            ],
            [
                'value' => StatusEnum::REJECTED,
                'message' => 'Elutasított'
            ],
            [
                'value' => StatusEnum::SENT,
                'message' => 'Elküldve'
            ],
            [
                'value' => StatusEnum::ARRIVED,
                'message' => 'Megérkezett'
            ],
            [
                'value' => StatusEnum::CANCELED,
                'message' => 'Visszavont'
            ],
        ];
    }

    static function getMessage($value) {
        foreach (StatusEnum::statusArray() as $status) {
            if($status['value'] == $value) {
                return $status['message'];
            }
        }
        return null;
    }
}

?>