<?php

namespace Osd\L4lHelpers\IP;

class IP
{
    /**
     * @return string|null
     */
    public static function real(): ?string
    {
        if (!empty($_SERVER['HTTP_CF_CONNECTING_IP'])) {
            return trim($_SERVER['HTTP_CF_CONNECTING_IP']);
        }

        if (!empty($_SERVER['HTTP_TRUE_CLIENT_IP'])) {
            return trim($_SERVER['HTTP_TRUE_CLIENT_IP']);
        }

        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return trim(explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0]);
        }

        return request()->ip();
    }

}
