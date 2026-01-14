<?php

namespace Osd\L4lHelpers\IP\Infrastructure;

use Osd\L4lHelpers\IP\Domain\Repository\IpLookupConfigRepository;

final class LaravelIpLookupConfigRepository implements IpLookupConfigRepository
{
    /**
     * @return bool
     */
    public function shouldPersist(): bool
    {
        return config('l4lhelpers.ip_lookup.persist', false);
    }

    /**
     * @return string
     */
    public function mode(): string
    {
        return config('l4lhelpers.ip_lookup.mode', 'default');
    }
}
