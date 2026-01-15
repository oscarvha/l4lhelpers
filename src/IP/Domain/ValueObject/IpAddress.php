<?php

namespace Osd\L4lHelpers\IP\Domain\ValueObject;

use InvalidArgumentException;

final readonly class IpAddress
{
    private function __construct(
        private string $value
    ) {}

    public static function fromString(string $ip): self
    {
        $ip = trim($ip);

        if (!filter_var($ip, FILTER_VALIDATE_IP)) {
            throw new InvalidArgumentException('Invalid IP address: ' . $ip);
        }

        return new self($ip);
    }

    public function value(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
