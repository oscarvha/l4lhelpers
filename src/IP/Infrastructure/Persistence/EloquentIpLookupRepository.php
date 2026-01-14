<?php

namespace Osd\L4lHelpers\IP\Infrastructure\Persistence;

use Illuminate\Support\Facades\Schema;
use Osd\L4lHelpers\IP\Domain\Models\IpLookup;
use Osd\L4lHelpers\IP\Domain\Repository\IpLookupRepository;
use Osd\L4lHelpers\IP\Infrastructure\Exceptions\MissingIpLookupTableException;
use Osd\L4lHelpers\IP\Infrastructure\Persistence\Models\IpLookUpModel;

class EloquentIpLookupRepository implements IpLookupRepository
{
    public function create(IpLookup $ipLookup): void
    {
        $this->checkTableExists();

        IpLookupModel::create([
            'id' => $ipLookup->uuid()->toString(),
            'ip' => $ipLookup->ip(),

            // GEO
            'geo_city' => $ipLookup->info()->geoLocation()->city(),
            'geo_country' => $ipLookup->info()->geoLocation()->country(),
            'geo_timezone' => $ipLookup->info()->geoLocation()->timeZone(),
            'geo_latitude' => $ipLookup->info()->geoLocation()->latitude(),
            'geo_longitude' => $ipLookup->info()->geoLocation()->longitude(),

            // OWNER
            'asn' => $ipLookup->owner()->asn(),
            'owner_name' => $ipLookup->owner()->name(),
            'owner_organization' => $ipLookup->owner()->organization(),
            'owner_country' => $ipLookup->owner()->country(),
            'rir' => $ipLookup->owner()->rir(),

            // NETWORK
            'cidr' => $ipLookup->owner()->range()->cidr(),
            'network_start_ip' => $ipLookup->owner()->range()->startIp(),
            'network_end_ip' => $ipLookup->owner()->range()->endIp(),
            'created_at' => $ipLookup->createdAt()->format('Y-m-d H:i:s'),
            'updated_at' => $ipLookup->updatedAt()->format('Y-m-d H:i:s'),
        ]);
    }

    public function createOrUpdate(IpLookup $ipLookup): void
    {
        $this->checkTableExists();

        IpLookupModel::updateOrCreate(
            ['ip' => $ipLookup->ip()],
            [
                'id' => $ipLookup->uuid()->toString(),

                // GEO
                'geo_city' => $ipLookup->info()->geoLocation()->city(),
                'geo_country' => $ipLookup->info()->geoLocation()->country(),
                'geo_timezone' => $ipLookup->info()->geoLocation()->timeZone(),
                'geo_latitude' => $ipLookup->info()->geoLocation()->latitude(),
                'geo_longitude' => $ipLookup->info()->geoLocation()->longitude(),

                // OWNER
                'asn' => $ipLookup->owner()->asn(),
                'owner_name' => $ipLookup->owner()->name(),
                'owner_organization' => $ipLookup->owner()->organization(),
                'owner_country' => $ipLookup->owner()->country(),
                'rir' => $ipLookup->owner()->rir(),

                // NETWORK
                'cidr' => $ipLookup->owner()->range()->cidr(),
                'network_start_ip' => $ipLookup->owner()->range()->startIp(),
                'network_end_ip' => $ipLookup->owner()->range()->endIp(),

                'created_at' => $ipLookup->createdAt()->format('Y-m-d H:i:s'),
                'updated_at' => $ipLookup->updatedAt()->format('Y-m-d H:i:s'),
            ]
        );
    }

    private function checkTableExists(): void
    {
        if (!Schema::hasTable('ip_lookups')) {
            throw new MissingIpLookupTableException(
                'IpLookup persistence is enabled but the ip_lookups table does not exist.'
            );
        }
    }
}
