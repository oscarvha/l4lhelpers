<?php

namespace Osd\L4lHelpers;

class Number
{
    /**
     * @param float $number
     * @return bool
     */
    public static function isExactNumber(float $number): bool
    {
        $intValue = (int)$number;

        return $number == $intValue;
    }
}
