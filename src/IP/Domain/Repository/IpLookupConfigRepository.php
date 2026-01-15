<?php

namespace Osd\L4lHelpers\IP\Domain\Repository;

interface IpLookupConfigRepository
{
    /**
     * @return bool
     */
    public function shouldPersist(): bool;

    /**
     * @return string
     */
    public function mode(): string;

    /**
     * @return int|null
     */
    public function refreshTtl(): ?int;

    public function spamAnalysisEnabled(): bool;

    public function spamAnalysisApiKey(): ?string;

    public function limitByIp() : ?int;

    public function limitByIpDurationMinutes() : int;
}
