<?php

namespace App\Enums;

class CountryEnum {

    const HUN = 'HUN';
    const SK = 'SK';

    static function valueArray() {
        return [
            CountryEnum::HUN, CountryEnum::SK,
        ];
    }

    static function countryArray() {
        return [
            [
                'iso' => CountryEnum::HUN,
                'name' => 'Magyarország'
            ],
            [
                'iso' => CountryEnum::SK,
                'name' => 'Szlovákia'
            ],
        ];
    }

    static function getName($iso) {
        foreach (CountryEnum::countryArray() as $country) {
            if($country['iso'] == $iso)
                return $country['name'];
        }
        return null;
    }
}

?>