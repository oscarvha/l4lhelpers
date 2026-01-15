<?php

namespace Osd\L4lHelpers\IP\Application\Exceptions;

class IpRequestLimitExceededException extends \RuntimeException
{
    public function __construct(
        string $ip,
        int $limit,
        int $windowMinutes
    ) {
        parent::__construct(
            sprintf(
                'IP request limit exceeded (%d requests / %d minutes) for IP: %s',
                $limit,
                $windowMinutes,
                $ip
            )
        );
    }
}
