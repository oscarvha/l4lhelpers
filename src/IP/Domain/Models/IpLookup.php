<?php

namespace Osd\L4lHelpers\IP\Domain\Models;

use Osd\L4lHelpers\IP\Domain\ValueObject\IpInfo;
use Osd\L4lHelpers\IP\Domain\ValueObject\IpLookupId;
use Osd\L4lHelpers\IP\Domain\ValueObject\IpNetworkOwner;

final readonly class IpLookup
{
    public function __construct(
        private IpLookupId $uuid,
        private string $ipAddress,
        private IpInfo $info,
        private IpNetworkOwner $owner,
        private ?\DateTimeImmutable $createdAt,
        private ?\DateTimeImmutable $updatedAt,
    )
    {}

    /**
     * @return IpLookupId
     */
    public function uuid(): IpLookupId
    {
        return $this->uuid;
    }

    /**
     * @return string
     */
    public function ip(): string
    {
        return $this->ipAddress;
    }

    /**
     * @return IpInfo
     */
    public function info(): IpInfo
    {
        return $this->info;
    }

    /**
     * @return IpNetworkOwner
     */
    public function owner(): IpNetworkOwner
    {
        return $this->owner;
    }

    public function createdAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }


}
