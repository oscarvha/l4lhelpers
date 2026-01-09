<?php

namespace Osd\L4lHelpers\IP\Domain\ValueObject;

final readonly class IpLookupId
{
    /**
     * @var string
     */
    private string $value;

    private function __construct(
       string $value
    ) {
        $this->value = $value;
    }

    /**
     * @param string $value
     * @return self
     */
    public static function fromString(string $value): self
    {
        return new self($value);
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        return $this->value;
    }
}
