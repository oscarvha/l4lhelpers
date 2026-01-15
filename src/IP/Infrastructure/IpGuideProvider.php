<?php

namespace Osd\L4lHelpers\IP\Infrastructure;

use Osd\IpLookup\Bootstrap\IpLookupFactory;
use Osd\L4lHelpers\IP\Domain\Contracts\IpProvider;
use Osd\L4lHelpers\IP\Domain\Models\IpLookup;
use Osd\L4lHelpers\IP\Domain\ValueObject\IpAddress;
use Osd\L4lHelpers\IP\Infrastructure\Mappers\IpLookupMapper;
use RuntimeException;

final class IpGuideProvider implements IpProvider
{
    /**
     * @param string $ip
     * @return IpLookup
     */
    public function fetch(string $ip): IpLookup
    {
        $ipAddress =  IpAddress::fromString($ip);

        $ipLook = IpLookupFactory::createDefault();
        $lookup = $ipLook->execute($ip);

        return IpLookupMapper::fromExternal($lookup,$ipAddress);

    }
}
