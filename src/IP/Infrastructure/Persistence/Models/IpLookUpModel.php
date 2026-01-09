<?php

namespace Osd\L4lHelpers\IP\Infrastructure\Persistence\Models;

use Illuminate\Database\Eloquent\Model;
use Osd\L4lHelpers\IP\Domain\ValueObject\IpNetworkOwnerRange;

class IpLookUpModel extends Model
{
    protected $table = 'ip_lookups';

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'ip',
        'geo_city',
        'geo_country',
        'geo_timezone',
        'geo_latitude',
        'geo_longitude',
        'asn',
        'owner_name',
        'owner_organization',
        'owner_country',
        'rir',
        'cidr',
        'network_start_ip',
        'network_end_ip',
        'created_at',
        'updated_at',
    ];


    public $timestamps = false;
}
