<?php

namespace Osd\L4lHelpers\IP\Infrastructure\Exceptions;

class MissingIpLookupTableException extends \RuntimeException
{
    public function __construct()
    {
        parent::__construct(
            'IpLookup persistence is enabled but the ip_lookups table does not exist. ' .
            'Did you forget to publish and run the migrations?'
        );
    }
}
