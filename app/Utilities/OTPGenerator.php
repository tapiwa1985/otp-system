<?php

namespace App\Utilities;

class OTPGenerator
{
    /**
     * @return string
     */
    public static function generateOTP(): string
    {
        return substr(str_shuffle("0123456789"), 0, 6);
    }
}
