<?php

namespace Osd\L4lHelpers\IP\Domain\Repository;

interface IpLookupConfigRepository
{
    public function shouldPersist(): bool;
    public function mode(): string;
}
