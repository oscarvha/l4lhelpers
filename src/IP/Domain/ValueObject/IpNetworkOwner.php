<?php

namespace Osd\L4lHelpers\IP\Domain\ValueObject;

final readonly class IpNetworkOwner
{
    public function __construct(
        private int $asn,
        private string $name,
        private string $organization,
        private string $country,
        private string $rir,
        private IpNetworkOwnerRange $range
    ) {
    }

    /**
     * @return int
     */
    public function asn(): int
    {
        return $this->asn;
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function organization(): string
    {
        return $this->organization;
    }

    /**
     * @return string
     */
    public function country(): string
    {
        return $this->country;
    }

    /**
     * @return string
     */
    public function rir(): string
    {
        return $this->rir;
    }

    /**
     * @return IpNetworkOwnerRange
     */
    public function range(): IpNetworkOwnerRange
    {
        return $this->range;
    }
}
