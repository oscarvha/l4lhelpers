<?php

namespace Osd\L4lHelpers\IP\Domain\Contracts;

use Osd\L4lHelpers\IP\Domain\Models\IpLookup;

interface IpProvider
{
    public function fetch(string $ip): IpLookup;

}
