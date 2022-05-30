<?php

namespace App\Enums;

class ValidatorMessageEnum {

    const REQUIRED = ' megadása kötelező';
    const STRING = ' csak szöveg lehet';
    const NUMERIC = ' csak szám lehet';
    const NUMERIC_MIN = ' legalább :min lehet';
    const NUMERIC_MAX = ' legfeljebb :max lehet';
    const STRING_MIN = ' legalább :min hosszú lehet';
    const STRING_MAX = ' legfeljebb :max hosszú lehet';
    const EMAIL = ' helytelen formátumú';
    const UNIQUE = ' már foglalt';
    const REGEX = ' helytelen formátumú';
    const PASSWORD = 'Helytelen jelszó';
    const CONFIRMED = ' nem egyezik';
    const EXISTS = ' nem létezik';
    const IMAGE = ' helytelen formátumú';
    const MAX_SIZE = ' legfeljebb :max kilobájt lehet';
    const IN = ' nem létezik.';
}

?>