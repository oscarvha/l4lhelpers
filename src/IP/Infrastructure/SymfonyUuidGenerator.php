<?php

namespace Osd\L4lHelpers\IP\Infrastructure;

use Osd\L4lHelpers\IP\Domain\Contracts\UuidGenerator;
use Symfony\Component\Uid\Uuid;

final class SymfonyUuidGenerator implements UuidGenerator
{
    /**
     * @return string
     */
    public function generate(): string
    {
        return Uuid::v4()->toRfc4122();

    }
}
