<?php

namespace Osd\L4lHelpers\IP\Domain\Repository;

use Osd\L4lHelpers\IP\Domain\Models\IpLookup;

interface IpLookupRepository
{
    public function create(IpLookup $ipLookup) : void;
    public function createOrUpdate(IpLookup $ipLookup) : void;
}
