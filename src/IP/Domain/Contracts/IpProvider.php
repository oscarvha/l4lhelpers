<?php

namespace Osd\L4lHelpers\IP\Domain\Contracts;

interface IpProvider
{
    public function fetch(string $ip): array;

}
