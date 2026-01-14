<?php

namespace Osd\L4lHelpers\IP\Infrastructure\Mappers;

use Osd\IpLookup\Domain\Models\IpLookup as ExternalIpLookup;
use Osd\L4lHelpers\IP\Domain\Models\IpLookup;
use Osd\L4lHelpers\IP\Domain\ValueObject\IpInfo;
use Osd\L4lHelpers\IP\Domain\ValueObject\IpInfoGeoLocation;
use Osd\L4lHelpers\IP\Domain\ValueObject\IpLookupId;
use Osd\L4lHelpers\IP\Domain\ValueObject\IpNetworkOwner;
use Osd\L4lHelpers\IP\Domain\ValueObject\IpNetworkOwnerRange;

final class IpLookupMapper
{
    public static function fromExternal(ExternalIpLookup $external): IpLookup
    {
        $range = new IpNetworkOwnerRange(
            $external->owner()->range()->cidr(),
            $external->owner()->range()->startIp(),
            $external->owner()->range()->endIp()
        );

        $owner = new IpNetworkOwner(
            $external->owner()->asn(),
            $external->owner()->name(),
            $external->owner()->organization(),
            $external->owner()->country(),
            $external->owner()->rir(),
            $range
        );

        $geo = $external->info()->geoLocation();

        if (!$geo) {
            throw new \InvalidArgumentException('GeoLocation data is missing in the external IpLookup object.');
        }

        $info = new IpInfo(
            $external->ip() ,
            new IpInfoGeoLocation(
                $geo->city(),
                $geo->country(),
                $geo->timezone(),
                $geo->latitude(),
                $geo->longitude()
            ),
        );

        return IpLookup::create(
            IpLookupId::fromString($external->uuid()->toString()),
            $external->ip(),
            $info,
            $owner
        );
    }}
