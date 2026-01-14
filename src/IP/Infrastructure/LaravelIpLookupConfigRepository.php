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

    /**
     * @return int|null
     */
    public function refreshTtl(): ?int
    {
        return config('l4lhelpers.ip_lookup.refresh_ttl', null);
    }

    public function spamAnalysisEnabled(): bool
    {
        return config('l4lhelpers.ip_lookup.spam_analysis.enabled', false);
    }

    public function spamAnalysisApiKey(): ?string
    {
        return config('l4lhelpers.ip_lookup.spam_analysis.api_key', null);
    }
}
