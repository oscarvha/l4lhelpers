<?php

namespace Osd\L4lHelpers\IP\Domain\Repository;

use Osd\L4lHelpers\IP\Domain\Models\IpLookup;

interface IpLookupRepository
{
    public function create(IpLookup $ipLookup,
                           ?string $requestedByIp = null
    ) : void;
    public function createOrUpdate(IpLookup $ipLookup,
                                   ?string $requestedByIp = null) : void;
    public function findByIpAddress(string $ipAddress) : ?IpLookup;

    public function countRequestByIp(string $ip, int $minutes) : int;
}
