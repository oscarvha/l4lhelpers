<?php

namespace Osd\L4lHelpers\IP\Domain\Models;

use Osd\L4lHelpers\IP\Domain\ValueObject\IPAddress;
use Osd\L4lHelpers\IP\Domain\ValueObject\IpInfo;
use Osd\L4lHelpers\IP\Domain\ValueObject\IpLookupId;
use Osd\L4lHelpers\IP\Domain\ValueObject\IpNetworkOwner;
use Osd\L4lHelpers\IP\Domain\ValueObject\IpSpamAssessment;

final class IpLookup
{
    private ?IpSpamAssessment $spamAssessment;

    private function __construct(
        private IpLookupId $uuid,
        private IPAddress $ipAddress,
        private IpInfo $info,
        private IpNetworkOwner $owner,
        private ?\DateTimeImmutable $createdAt,
        private ?\DateTimeImmutable $updatedAt
    )
    {
        $this->spamAssessment = null;
    }

    /**
     * @param int|null $ttlSeconds
     * @param \DateTimeImmutable $now
     * @return bool
     */
    public function isExpired(?int $ttlSeconds, \DateTimeImmutable $now): bool
    {
        if ($ttlSeconds === null) {
            return false;
        }

        if ($this->updatedAt === null) {
            return true;
        }

        return $this->updatedAt->modify("+{$ttlSeconds} seconds") < $now;
    }

    /**
     * @return IpLookupId
     */
    public function uuid(): IpLookupId
    {
        return $this->uuid;
    }

    /**
     * @return IPAddress
     */
    public function ip(): IpAddress
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


    public function getSpamAssessment(): ?IpSpamAssessment
    {
        return $this->spamAssessment;
    }


    public function setSpamAssessment(IpSpamAssessment $assessment): void
    {
        $this->spamAssessment = $assessment;
    }

    /**
     * @param IpLookupId $id
     * @param IPAddress $ip
     * @param IpInfo $info
     * @param IpNetworkOwner $owner
     * @return self
     */
    public static function create(
        IpLookupId $id,
        IPAddress $ip,
        IpInfo $info,
        IpNetworkOwner $owner
    ): self {

        return new self(
            $id,
            $ip,
            $info,
            $owner,
            new \DateTimeImmutable(),
            new \DateTimeImmutable(),
        );
    }


    /**
     * @param IpLookupId $id
     * @param IPAddress $ip
     * @param IpInfo $info
     * @param IpNetworkOwner $owner
     * @param \DateTimeImmutable|null $createdAt
     * @param IpSpamAssessment|null $spamAssessment
     * @return self
     */
    public static function recreate(
        IpLookupId $id,
        IPAddress $ip,
        IpInfo $info,
        IpNetworkOwner $owner,
        ?\DateTimeImmutable $createdAt,
        IpSpamAssessment $spamAssessment = null
    ): self {

        $self =  new self(
            $id,
            $ip,
            $info,
            $owner,
            $createdAt,
            new \DateTimeImmutable());

            if($spamAssessment !== null) {
                $self->setSpamAssessment($spamAssessment);
            }

        return $self;
    }
}
