<?php

namespace Osd\L4lHelpers\IP\Domain\ValueObject;

final readonly class IpNetworkOwnerRange
{
    public function __construct(
        private string $cidr,
        private string $startIp,
        private string $endIp
    ) {}

    /**
     * @return string
     */
    public function cidr(): string
    {
        return $this->cidr;
    }

    /**
     * @return string
     */
    public function startIp(): string
    {
        return $this->startIp;
    }

    /**
     * @return string
     */
    public function endIp(): string
    {
        return $this->endIp;
    }

}
