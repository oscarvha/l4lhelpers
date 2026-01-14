<?php

namespace Osd\L4lHelpers\IP\Domain\Contracts;

use Osd\L4lHelpers\IP\Domain\Models\IpLookup;

interface IpLookupService
{
    /**
     * @param string $ip
     * @return IpLookup
     */
    public function lookup(string $ip): IpLookup;
}
